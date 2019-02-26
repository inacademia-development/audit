#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
echo $DIR
cd $DIR
source config/audit_mysql.cnf
mysql -h$MYSQL_HOST -u$MYSQL_USER -p$MYSQL_PWD $MYSQL_DB
