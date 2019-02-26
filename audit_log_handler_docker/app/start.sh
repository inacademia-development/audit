#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
echo $DIR
cd $DIR
#source ../env

# For debugging
echo "Connecting to database using:" 
echo $MYSQL_HOST
echo $MYSQL_DATABASE
echo $MYSQL_USER
echo $MYSQL_ROOT_PASSWORD

/usr/bin/python ./parse_audit_logs.py $MYSQL_HOST $MYSQL_USER $MYSQL_ROOT_PASSWORD $MYSQL_DATABASE
