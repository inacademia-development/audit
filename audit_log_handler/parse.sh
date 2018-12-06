#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
echo $DIR
cd $DIR
source ../env
./parse_audit_logs.py $MYSQL_HOST $MYSQL_USER $MYSQL_PWD $MYSQL_DB
