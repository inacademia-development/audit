# audit
Audit tools

# Install
Create python virtualenv for parser:
```
$ virtualenv audit
```
Install requirements:
```
$ . audit/bin/activate
(audit) $ pip install -r audit_log_handler/requirements.txt
```
Update enviroment:
```
$ cp env.example env
$ vi env
```
# Run
Run mysql server:
```
$ inac-audit-mysql/run_mysql.sh
```
Connect mysql server:
```
$ inac-audit-mysql/connect_mysql.sh
```
Parse logfiles (needs appropriate logfiles in place)
```
$ . audit/bin/activate
(audit) $ audit_log_handler/parse.sh
```
Run (test) webserver:
```
$ audit_log_handler/site.sh
```
Check audit: http://localhost:8080/
