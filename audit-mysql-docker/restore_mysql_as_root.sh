#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
echo $DIR
cd $DIR
source config/audit_mysql.cnf
#mysql -h$MYSQL_HOST -uroot -p$MYSQL_PWD $MYSQL_DB
/usr/bin/mysqldump -h$MYSQL_HOST -uroot -p$MYSQL_PWD $MYSQL_DB < $MYSQL_DB.sql
