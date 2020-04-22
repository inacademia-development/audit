#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
echo $DIR
cd $DIR
source config/audit_mysql.cnf

IMAGE_TAG=mysql:latest
CONTAINER_NAME=inacademia_mysqllogs

# Start MySQL image
docker start -i $CONTAINER_NAME || docker run -it \
     --net inacademia.local \
     --ip $MYSQL_HOST \
     -e MYSQL_ROOT_PASSWORD=$MYSQL_PWD \
     -e MYSQL_DATABASE=$MYSQL_DB \
     -e MYSQL_USER=$MYSQL_USER \
     -e MYSQL_PASSWORD=$MYSQL_PWD \
     -v "$(pwd)/config/sql:/docker-entrypoint-initdb.d" \
     -d \
     --name $CONTAINER_NAME \
     $IMAGE_TAG \
     --default-authentication-plugin=mysql_native_password

# COPY setup.sql /docker-entrypoint-initdb.d/
#      -p 3306:3306 \
#     -v $DIR/mysql_data_dir:/var/lib/mysql \
#     --name $CONTAINER_NAME \
#     --net inacademia.local \


