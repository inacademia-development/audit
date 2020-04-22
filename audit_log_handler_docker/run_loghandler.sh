#! /bin/bash
RUN_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
cd $RUN_DIR
source config/audit_handler.cnf

IMAGE_TAG=inacademia/log_handler:v1

export KEY_PATH=$GIT_KEY_PATH

# Build the docker image if needed
if [[ "$(docker images -q $IMAGE_TAG 2> /dev/null)" == "" ]]; then
  docker build -t $IMAGE_TAG .
fi

# Pull in audit log data from gitlab.geant.org:inacademia/audit_logs.git
# We are pulling this data outside of the container so it can persist in a local file storage for efficiency
# Then use volume mount to RO privide data to the continer
echo "###################################################################################"
echo " Pulling audit log data"
echo "###################################################################################"
if [ ! -d "$REMOTE_DIR" ]; then
	# If the remote dir does not exists, pull a fresh copy of tha audot logs into the remote dir
	echo "$REMOTE_DIR not found, pulling new copy of audit logs"
	GIT_SSH_COMMAND='ssh -i "${KEY_PATH}"' /usr/bin/git clone git@gitlab.geant.org:inacademia/audit_logs.git $REMOTE_DIR
else
	# Remote dir exists, so cd into the remote dir and pull the lates version of the logspull a fresh copy of tha audot logs into the remote dir
	echo "$REMOTE_DIR found, updating audit logs"
	cd $REMOTE_DIR
	GIT_SSH_COMMAND='ssh -i "${KEY_PATH}"' /usr/bin/git pull git@gitlab.geant.org:inacademia/audit_logs.git
fi

# Pull in admin data from git@gitlab.geant.org:inacademia/admin_data.git
# We are pulling this data outside of the container so it can persist in a local file storage for efficiency
# Then use volume mount to RO privide data to the continer
echo "###################################################################################"
echo " Pulling admin data"
echo "###################################################################################"

if [ ! -d "$ADMIN_DATA_DIR" ]; then
	# If the remote dir does not exists, pull a fresh copy of tha audot logs into the remote dir
	echo "$ADMIN_DATA_DIR not found, pulling new copy of admin data logs"
	GIT_SSH_COMMAND='ssh -i "${KEY_PATH}"' /usr/bin/git clone git@gitlab.geant.org:inacademia/admin_data_dev.git $ADMIN_DATA_DIR
else
	# Remote dir exists, so cd into the remote dir and pull the lates version of the logspull a fresh copy of tha audot logs into the remote dir
	echo "$ADMIN_DATA_DIR found, updating audit logs"
	cd $ADMIN_DATA_DIR
	GIT_SSH_COMMAND='ssh -i "${KEY_PATH}"' /usr/bin/git pull git@gitlab.geant.org:inacademia/admin_data_dev.git
fi

# Pull in idphint data from git@gitlab.geant.org:inacademia/admin_data.git
# We are pulling this data outside of the container so it can persist in a local file storage for efficiency
# Then use volume mount to RO privide data to the continer
echo "###################################################################################"
echo " Pulling idp_hint data"
echo "###################################################################################"

if [ ! -d "$IDP_HINT_DIR" ]; then
	# If the remote dir does not exists, pull a fresh copy of tha audot logs into the remote dir
	echo "$IDP_HINT_DIR not found, pulling new copy of idp hint data"
	GIT_SSH_COMMAND='ssh -i "${KEY_PATH}"' /usr/bin/git clone https://github.com/InAcademia/idp_hint.git $IDP_HINT_DIR
else
	# Remote dir exists, so cd into the remote dir and pull the lates version of the logspull a fresh copy of tha audot logs into the remote dir
	echo "$IDP_HINT_DIR found, updating idp hint data"
	cd $IDP_HINT_DIR
	GIT_SSH_COMMAND='ssh -i "${KEY_PATH}"' /usr/bin/git pull https://github.com/InAcademia/idp_hint.git
fi

cp $IDP_HINT_DIR/display_names.json $ADMIN_DATA_DIR/display_names.json

# Start Docker
docker run -it \
	--ip $LOGHANDLER_HOST_IP \
	--net inacademia.local \
	-v /etc/passwd:/etc/passwd:ro \
	-v /etc/group:/etc/group:ro \
	-v /home/ubuntu:/home/ubuntu \
	-v $REMOTE_DIR:/tmp/remote:ro \
	-v $ADMIN_DATA_DIR:/tmp/inacademia_admin_data:ro \
        -v $CDB_DIR:/tmp/cdb:ro \
	-v $RUN_DIR/app:/tmp/inacademia \
        -e MYSQL_ROOT_PASSWORD=$MYSQL_PWD \
        -e MYSQL_DATABASE=$MYSQL_DB \
        -e MYSQL_USER=$MYSQL_USER \
        -e MYSQL_HOST=$MYSQL_HOST \
	--hostname log_handler.inacademia.local \
	$IMAGE_TAG

#       -v $REMOTE_DIR:/tmp/remote:ro \
#    	-e MYSQL_IP=$MYSQL_IP \
#	--mount src="$(pwd)/config",target=/tmp/config,type=bind \
#	--mount source=inacademia_admin_data,target=/tmp/inacademia_admin_data \
#     -v $IDP_HINT_DIR:/tmp/idp_hint_data:ro \
