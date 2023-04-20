#!/bin/bash

# The data to place in the custom vhost.conf file.
vhost_data="
<VirtualHost 127.0.0.1:80 _default_:80>
ServerAlias *
DocumentRoot /opt/bitnami/APPNAME
<Directory "/opt/bitnami/APPNAME">
Options -Indexes +FollowSymLinks -MultiViews
AllowOverride All
Require all granted
</Directory>
</VirtualHost>"

# The data to place in the custom https-vhost.conf file.
vhost_https_data=""

# Validating that this is a Bitnami instance.
if [ -d "/opt/bitnami/apache/conf/vhosts" ]; then
	echo "This is a Bitnami instance."
else
	echo "This installer is meant for Bitnami-hosted LAMP servers."
	echo "Please run the appropriate installer."
fi