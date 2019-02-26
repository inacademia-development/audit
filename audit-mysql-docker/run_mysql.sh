#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
echo $DIR
cd $DIR
source config/audit_mysql.cnf

IMAGE_TAG=mysql:latest
CONTAINER_NAME=inacademia_mysqllogs

# Start MySQL image
docker start -i $CONTAINER_NAME || docker run -it \
     --default-authentication-plugin=mysql_native_password
     --net inacademia.local \
     --ip $MYSQL_IP \
     -e MYSQL_ROOT_PASSWORD=$MYSQL_PWD \
     -e MYSQL_DATABASE=$MYSQL_DB \
     -e MYSQL_USER=$MYSQL_USER \
     -e MYSQL_PASSWORD=$MYSQL_PWD \
     -d \
     $IMAGE_TAG



#      -p 3306:3306 \
#     -v $DIR/mysql_data_dir:/var/lib/mysql \
#     --name $CONTAINER_NAME \
#     --net inacademia.local \


