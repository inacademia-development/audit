#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
cd $DIR
source config/audit_handler.cnf

IMAGE_TAG=inacademia/log_handler:v1

# Build the docker image if needed
if [[ "$(docker images -q $IMAGE_TAG 2> /dev/null)" == "" ]]; then
  docker build -t $IMAGE_TAG .
fi

# find the location of configs in current directory structure
RUN_DIR=$PWD

# Start SVS
docker run -it \
	--ip $LOGHANDLER_HOST_IP \
	--net inacademia.local \
	-v /etc/passwd:/etc/passwd:ro \
	-v /etc/group:/etc/group:ro \
	-v /home/ubuntu:/home/ubuntu \
        -v $REMOTE_DIR:/tmp/remote:ro \
        -v $CDB_DIR:/tmp/cdb:ro \
        -e MYSQL_ROOT_PASSWORD=$MYSQL_PWD \
     	-e MYSQL_DATABASE=$MYSQL_DB \
     	-e MYSQL_USER=$MYSQL_USER \
     	-e MYSQL_HOST=$MYSQL_HOST \
	--mount source=inacademia_admin_data,target=/tmp/inacademia_admin_data \
	--hostname log_handler.inacademia.local \
	$IMAGE_TAG


#       -v $REMOTE_DIR:/tmp/remote:ro \
#    	-e MYSQL_IP=$MYSQL_IP \
#	--mount src="$(pwd)/config",target=/tmp/config,type=bind \
