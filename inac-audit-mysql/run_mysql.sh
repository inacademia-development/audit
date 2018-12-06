#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
echo $DIR
cd $DIR
source ../env

IMAGE_TAG=mysql:latest
CONTAINER_NAME=inacademia_mysqllogs

# Start MySQL image
docker start -i $CONTAINER_NAME || docker run -it \
     --name $CONTAINER_NAME \
     --net inacademia.local \
     --ip $MYSQL_HOST \
     -e MYSQL_ROOT_PASSWORD=$MYSQL_PWD \
     -e MYSQL_DATABASE=$MYSQL_DB \
     -e MYSQL_USER=$MYSQL_USER \
     -e MYSQL_PASSWORD=$MYSQL_PWD \
     -d \
     $IMAGE_TAG \
     --default-authentication-plugin=mysql_native_password
#      -p 3306:3306 \
#      -v /srv/docker/inac-audit-mysql/data:/var/lib/mysql \
