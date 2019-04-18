FROM ubuntu:16.04
MAINTAINER InAcademia Team, tech@inacademia.org

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    python \
    python-mysqldb \
    python-dateutil
RUN apt clean

#Copy over start and parse script
#COPY app/start.sh /tmp/inacademia/start.sh
#COPY app/parse_audit_logs.py /tmp/inacademia/parse_audit_logs.py

#ENTRYPOINT ["/tmp/inacademia/start.sh"]
ENTRYPOINT ["/bin/bash"]

