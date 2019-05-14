#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
cd $DIR
source config/audit_stats.cnf

IMAGE_TAG=inacademia/stats:v1

# Build the docker image if needed
if [[ "$(docker images -q $IMAGE_TAG 2> /dev/null)" == "" ]]; then
  docker build -t $IMAGE_TAG .
fi

# fetsh the correct stats software and put it i app/audit
cd app/
/usr/bin/git clone -b $STATS_TAG git@github.com:inacademia-development/stats.git || /usr/bin/git checkout -b $STATS_TAG  && /usr/bin/git pull origin $STATS_TAG

# find the location of configs in current directory structure
RUN_DIR=$PWD

# Start stats amd expose on port 8080
docker run -it \
	--hostname stats.inacademia.local \
	--ip $STATS_HOST_IP \
	--net inacademia.local \
	-v $RUN_DIR/app/stats:/var/www/html/stats \
	-p 8080:80 \
	-e MYSQL_PWD=$MYSQL_PWD \
    -e MYSQL_DB=$MYSQL_DB \
    -e MYSQL_USER=$MYSQL_USER \
    -e MYSQL_HOST=$MYSQL_HOST \
	$IMAGE_TAG
