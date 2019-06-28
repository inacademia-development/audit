#! /bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
STATS_DIR="$DIR/../../stats"
source $DIR/config/audit_stats.cnf
IMAGE_TAG=inacademia/stats:v1

cd $DIR

# Build the docker image if needed
if [[ "$(docker images -q $IMAGE_TAG 2> /dev/null)" == "" ]]; then
  docker build -t $IMAGE_TAG .
fi

# fetsh the correct stats software and put it i app/audit
cd app/
/usr/bin/git clone -b $STATS_TAG git@github.com:inacademia-development/stats.git || /usr/bin/git checkout -b $STATS_TAG  && /usr/bin/git pull origin $STATS_TAG

# fetch the correct portal version
if [ -d "$STATS_DIR" ]; then
  echo "STATS Directory $STATS_DIR exists!, Updating to version/branch $STATS_TAG" 
  cd "$STATS_DIR"
  #/usr/bin/git checkout $STATS_TAG  && /usr/bin/git pull origin $STATS_TAG
else
  echo "$STATS_DIR DOES NOT exists!, Cloning version/branch $STATS_TAG"
  cd $DIR/../..
  /usr/bin/git clone -b $STATS_TAG git@github.com:inacademia-development/stats.git
fi

# Start stats amd expose on port 8080
docker run -it \
	--hostname stats.inacademia.local \
	--ip $STATS_HOST_IP \
	--net inacademia.local \
	-v $DIR/../../stats:/var/www/html/stats \
	-v $DIR/config/:/tmp/inacademia/config \
	-p 80:80 \
	-p 443:443 \
	-e MYSQL_PWD=$MYSQL_PWD \
    -e MYSQL_DB=$MYSQL_DB \
    -e MYSQL_USER=$MYSQL_USER \
    -e MYSQL_HOST=$MYSQL_HOST \
	$IMAGE_TAG
