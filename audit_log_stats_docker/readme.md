To build the container, run the build_stats.sh script.

Before runnning the container, it needs to be provided with a configuration matching your environment.

Start by creating a copy fo the example configuration:
`cp -R config.example config`

The config directory has the following structure:
```
├── apache
│   ├── stats.crt
│   ├── stats.key
│   └── stats_ssl.conf
├── audit_stats.cnf
└── simplesamlphp
    ├── authsources.php
    ├── config.php
    ├── saml20-idp-remote.php
    ├── saml.crt
    └── saml.pem
```

## General configuration
The audit_stats.cnf file contains some generic configuration of the setup and the application.
*You MUST update this file with your local MySQL db connection data and credentials.*

The stats application that gets deployed into the docker container is explicitly configured by naming the version tag. If you do not know what to use, leave it to 'master'

## Apache configuration
The apache webserver is used to provide the application. As https is used, you need to provide both an ssl stats.key and starts.crt certificate which you should name as indicated.

The apache config for the stats host is described in the stats_ssl.conf. Please make sure the ServerName parameter matches the FQDN of your server. All other parameters may probably be left untouched.

## Simpelsamlphp
We use SimplesSAMLphp (ssp) to handle SAML based authentication for the stats.
*In config.php you MUST change values the following 2 options to secure your installation:*
```
 'secretsalt' => 'xxxxxxxxxxxxxxxx',
 'auth.adminpassword' => 'xxxxxxxxxxxx',
```

For signing and encrypting the SAML messages, ssp needs a certificate. You should create a self signed certificate with a long lifetime (5-10 years) for a production service and put the key and cert in this directory with the files matching the names presented. Do not use the https certificates for this!

To connect this service to a remote IdP you need to add the remote IdP metadata to the saml20-idp-remote.php file. By default the Terena/Geant IdP proxy is configured already.
To complete the SAML registration you also need to provide the IdP with our metadata. To do so, please finish the deployment and run the instance. 
The metadata you need to proved to the IdP admin can be found at 
`https://fqdn_hostname/simplesaml/module.php/saml/sp/metadata.php/default-sp`







