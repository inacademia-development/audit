#!/usr/bin/env python
import os
import json
import time
import sys
import dateutil.parser
from os import listdir
from os.path import isfile, join, dirname, realpath
import MySQLdb

BASE_DIR = "/tmp"
LOGS_DIR = BASE_DIR + "/remote"
IDPS_DIR = BASE_DIR + "/inacademia_admin_data"
IDPS_HASH_FILE = IDPS_DIR + "/entityids.json"
IDPS_NAME_FILE = IDPS_DIR + "/display_names.json"
IDPS_COUNTRY_FILE = IDPS_DIR + "/entityids_country.json"
IDPS_RA_FILE = IDPS_DIR + "/entityids_ra.json"
CLIENTS_FILE = BASE_DIR + "/cdb/cdb.json"
SERVER_BLACKLIST = ['.git', '127.0.0.1']
EXCLUDE_LINES_WITH = ['kernel', 'sudo', 'sshd', 'rsyslogd', 'testid', 'DEBUG', 'INFO', 'WARNI']

UPDATE_LOGS = 1
UPDATE_IDPS = 1
UPDATE_CLIENTS = 1

if len(sys.argv) < 5:
    sys.exit("Need MYSQL_HOST MYSQL_USER MYSQL_PWD MYSQL_DB")

MYSQL_HOST = sys.argv[1]
MYSQL_USER = sys.argv[2]
MYSQL_PWD  = sys.argv[3]
MYSQL_DB   = sys.argv[4]

def p(message):
    print(message)

def find_strings_in_line(strings, a_line):
    for string in strings:
        if string in a_line:
            return True
    return False

def parse_timezone(datetime):
    t = dateutil.parser.parse(datetime)
    return t.strftime('%Y-%m-%d %H:%M:%S')

def parse_audit_line(a_line, exclude_lines_with):
    audit_dict = {}

    if not find_strings_in_line(exclude_lines_with, a_line):
        try:
            a_line.rstrip('\n')
            char_pos = a_line.index("{")
            end_pos = a_line.rindex("}") + 1
            audit_json = json.loads(a_line[char_pos:end_pos])

            audit_dict['timestamp'] = audit_json['timestamp']
            audit_dict['sessionid'] = audit_json['sessionid']
            audit_dict['idp'] = audit_json['idp']
            audit_dict['sp'] = audit_json['sp']
            # domain can have metadata e.g. "domain": ["access-check.edugain.org, faculty"]
            if audit_json['attr']['domain'] != None:
                audit_dict['domain'] = audit_json['attr']['domain'][0].split(',')[0]
            else:
                audit_dict['domain'] = 'unknown'
            # after we fixe it
            #audit_dict['domain'] = audit_json['attr']['domain'][0]
            if audit_json['attr']['affiliation'] != None:
                audit_dict['affiliations'] = audit_json['attr']['affiliation'][0]
            else:
                audit_dict['affiliations'] = ''

            return audit_dict
        except:
            p("Could not parse line %s" % (a_line))
    else:
        return None

def parse_audit_file(filename, exclude_lines_with):
    stack = []

    with open(filename) as f:
        for line in f:
            parsed_line = parse_audit_line(line, exclude_lines_with)
            if parsed_line:
                stack.append(parsed_line)

        return stack

def parse_idps_file(filename, exclude_lines_with):
    with open(filename) as f:
        data = json.load(f)

    return data

def parse_idps_name_file(filename, exclude_lines_with):
    with open(filename) as f:
        data = json.load(f)

    return data

def parse_idps_country_file(filename, exclude_lines_with):
    with open(filename) as f:
        data = json.load(f)

    return data

def parse_clients_file(filename, exclude_lines_with):
    clients_dict = {}

    with open(filename) as f:
        data = json.load(f)

    for key in data.keys():
      try:
        clients_dict[key] = data[key]["display_name"]
      except KeyError:
        clients_dict[key] = key

    return clients_dict

def get_subdirectories(a_dir, blacklist):
    return [name for name in os.listdir(a_dir)
            if name not in blacklist and os.path.isdir(os.path.join(a_dir, name))]

def get_files(a_dir):
    files = []
    for f in listdir(a_dir):
        if isfile(join(a_dir, f)):
            files.append(f)
    return files

def write_logs_sql(cur, audit_dict):
    # Create a Cursor object
    #cur = db.cursor()
    #cur.execute('SET NAMES utf8;')
    #cur.execute('SET CHARACTER SET utf8;')
    #cur.execute('SET character_set_connection=utf8;')

    affiliations = audit_dict.get('affiliations', '')
    ts = parse_timezone(audit_dict.get('timestamp'))
    query = ("INSERT INTO `" + MYSQL_DB + "`.`logs` ("
             "`log_timestamp`, `log_sessionid`, `log_domain`, `log_sp`, `log_idp`, `log_affiliate`, `log_employee`, `log_member`, `log_faculty`, `log_staff`, `log_student`) "
             "VALUES ("
             "'" + ts + "',"
             "'" + audit_dict.get('sessionid')                    + "',"
             "'" + audit_dict.get('domain')                       + "',"
             "'" + audit_dict.get('sp')                           + "',"
             "'" + audit_dict.get('idp')                          + "',"
             + str('affiliate' in affiliations)                   + ","
             + str('employee' in affiliations)                    + ","
             + str('member' in affiliations)                      + ","
             + str('faculty' in affiliations)                     + ","
             + str('staff' in affiliations)                       + ","
             + str('student' in affiliations)                     + ") "
             "ON DUPLICATE KEY UPDATE "
             "`log_timestamp`='" + ts + "',"
             "`log_domain`='" + audit_dict.get('domain')           + "',"
             "`log_sp`='" + audit_dict.get('sp')                   + "',"
             "`log_idp`='" + audit_dict.get('idp')                 + "',"
             "`log_affiliate`=" + str('affiliate' in affiliations) + ","
             "`log_employee`="  + str('employee' in affiliations)  + ","
             "`log_member`=" + str('member' in affiliations)       + ","
             "`log_faculty`=" + str('faculty' in affiliations)     + ","
             "`log_staff`=" + str('staff' in affiliations)         + ","
             "`log_student`=" + str('student' in affiliations)
            ).encode('utf-8')

    try:
        cur.execute(query)
    except MySQLdb.Error as e:
        p("Query: " + query)
        p("Error:%d:%s" % (e.args[0], e.args[1]))

def write_idps_sql(cur, key, name, domain, country, ra, idphash):
    # Create a Cursor object
    #cur = db.cursor()
    #cur.execute('SET NAMES UTF8MB4;')
    #cur.execute('SET CHARACTER SET utf8;')
    #cur.execute('SET character_set_connection=UTF8MB4;')

    name = name.replace("'", "\\'")
    query = ("INSERT INTO `" + MYSQL_DB + "`.`idps` "
             " ( `idp_entityid`, `idp_displayname`, `idp_domain`, `idp_country`, `idp_ra`, `idp_hash`)  "
             " VALUES "
             " ( '"  + key + "',"
             "   '"  + name + "',"
             "   '"  + domain  + "',"
             "   '"  + country  + "',"
             "   '"  + ra  + "',"
             "   '"  + idphash  + "') "
             "ON DUPLICATE KEY UPDATE "
             "`idp_displayname`='" + name + "',"
             "`idp_domain`='" + domain + "',"
             "`idp_country`='" + country + "',"
             "`idp_ra`='" + ra + "'"
            ).encode('utf-8')
    try:
        cur.execute(query)
    except MySQLdb.Error as e:
        p("Query: " + query)
        p("Error:%d:%s" % (e.args[0], e.args[1]))

def write_client_sql(cur, key, name):
    # Create a Cursor object
    #cur = db.cursor()
    #cur.execute('SET NAMES UTF8MB4;')
    #cur.execute('SET CHARACTER SET utf8;')
    #cur.execute('SET character_set_connection=UTF8MB4;')

    query = ("INSERT INTO `" + MYSQL_DB + "`.`clients` "
             " ( `client_name`, `client_displayname`)  "
             " VALUES "
             " ( '"  + key + "',"
             "   '"  + name  + "')"
             "ON DUPLICATE KEY UPDATE "
             "`client_displayname`='" + name + "'"
            ).encode('utf-8')
    try:
        cur.execute(query)
    except MySQLdb.Error as e:
        p("Query: " + query)
        p("Error:%d:%s" % (e.args[0], e.args[1]))

# MAIN
db = MySQLdb.connect(host=MYSQL_HOST,    # your host, usually localhost
                     user=MYSQL_USER,         # your username
                     passwd=MYSQL_PWD,  # your password
                     db=MYSQL_DB)        # name of the data base

db.autocommit(True)
db.set_character_set('utf8')

cur = db.cursor()
cur.execute('SET NAMES UTF8MB4;')
cur.execute('SET CHARACTER SET utf8;')
cur.execute('SET character_set_connection=UTF8MB4;')

if UPDATE_LOGS:
    server_dirs = get_subdirectories(LOGS_DIR, SERVER_BLACKLIST)

    for server_dir in server_dirs:
        p("+ %s " % (server_dir))

        log_files = get_files(LOGS_DIR + "/" + server_dir)

        for log_file in log_files:
            p("+- %s " % (log_file))
            audit_dict = parse_audit_file(LOGS_DIR + "/" + server_dir + "/" + log_file, EXCLUDE_LINES_WITH)

            if len(audit_dict) != 0:
                while audit_dict:
                    #try:
                    write_logs_sql(cur, audit_dict.pop())
                    #except:
                        #p("Issue with db write for %s" % audit_dict)

if UPDATE_IDPS:
    idps_dict = parse_idps_file(IDPS_HASH_FILE, "")
    idps_name_dict = parse_idps_name_file(IDPS_NAME_FILE, "")
    idps_country_dict = parse_idps_country_file(IDPS_COUNTRY_FILE, "")
    idps_ra_dict = parse_idps_country_file(IDPS_RA_FILE, "")

    for key in idps_dict:
        p(key)
        try:
            #write_idps_sql(cur, key, idps_dict[key], "unknown", idps_country_dict.get(key, "unknown"))
            write_idps_sql(cur, idps_dict.get(key, "unknown"), idps_name_dict.get(key, "unknown"), "unknown", idps_country_dict.get(key, "xx"), idps_ra_dict.get(key, "unknown"), key)
        except Exception as e:
            p("Issue with db write for %s, %s" % (key, e.message))

if UPDATE_CLIENTS:
    clients_dict = parse_clients_file(CLIENTS_FILE, "")

    for key in clients_dict:
        p(key)
        try:
            write_client_sql(cur, key, clients_dict[key])
        except Exception as e:
            p("Issue with db write for %s, %s" % (key, e.message))

#cur.close()
db.close()




