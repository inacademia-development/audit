#! /bin/bash

DATE=`date +%Y-%m-%d`

rm -Rf /tmp/remote
rm -Rf /tmp/remote_${DATE}

mkdir /tmp/remote_${DATE}

scp inac.loghost2:/home/ubuntu/remote_${DATE}.tgz /tmp/remote_${DATE}/

cd /tmp/remote_${DATE}/
tar xvfz remote_${DATE}.tgz

ln -s /tmp/remote_${DATE}/var/log/remote /tmp/remote
