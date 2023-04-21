#!/bin/bash

# The data to place in the custom vhost.conf file.
vhost_data='
	<VirtualHost 127.0.0.1:80 _default_:80>
		ServerAlias *
		DocumentRoot /opt/bitnami/kosmoss/public
		<Directory "/opt/bitnami/kosmoss/public">
			Options -Indexes +FollowSymLinks -MultiViews
			AllowOverride All
			Require all granted
		</Directory>
	</VirtualHost>
'

# The data to place in the custom https-vhost.conf file.
vhost_https_data='
	<VirtualHost 127.0.0.1:443 _default_:443>
	    ServerAlias *
	    DocumentRoot /opt/bitnami/kosmoss/public
	    SSLEngine on
	    SSLCertificateFile "/opt/bitnami/apache/conf/bitnami/certs/server.crt"
	    SSLCertificateKeyFile "/opt/bitnami/apache/conf/bitnami/certs/server.key"
	    <Directory "/opt/bitnami/kosmoss/public">
	    	Options -Indexes +FollowSymLinks -MultiViews
	    	AllowOverride All
	    	Require all granted
	    </Directory>
	</VirtualHost>
'

# Validating that this is a Bitnami instance.
if [ -d "/opt/bitnami/apache/conf/vhosts" ]; then
  # Detecting if Kosmoss is already installed on this machine.
  if [ -d "/opt/bitnami/kosmoss" ]; then
    echo "Existing Kosmoss installation detected, would you like to overwrite it?"
    echo -n "Type 'yes' to overwrite the existing Kosmoss installation: "
    read -r response
    if [ "$response" != 'yes' ]; then
      echo 'Terminating installation...'
      exit 0
    fi

    echo "Deleting existing Kosmoss installation..."
    rm -rf /opt/bitnami/kosmoss
  fi

  # Pulling data from GitHub.
	echo "Cloning latest from https://github.com/james-minor/kosmoss..."
	git clone https://github.com/james-minor/kosmoss /opt/bitnami/kosmoss

  # Writing the custom VHost Configuration files.
	echo "Writing VirtualHost configurations..."
	echo "$vhost_data" > /opt/bitnami/apache/conf/vhosts/kosmoss-vhosts.conf
	echo "$vhost_https_data" > /opt/bitnami/apache/conf/vhosts/kosmoss-https-vhosts.conf

  # Creating the vendor directory for Composer.
  echo "Creating vendor directory..."
  sudo mkdir /opt/bitnami/kosmoss/vendor

  # Creating the .env file.
  if [ ! -f /opt/bitnami/kosmoss/.env ]; then
    echo "No .env file was found in /opt/bitnami/kosmoss"
    echo "Type 'yes' if you would like the installer to help initialize the .env file."
    read -r response

    # If the user wants to run the .env helper.
    if [ "$response" == 'yes' ]; then
      # Gathering the values for the .env keys.
      echo -n "Please enter your SQL Hostname (leave blank for default 127.0.0.1): "
      read -r hostname
      echo -n "Please enter your SQL Username: "
      read -r username
      echo -n "Please enter your SQL Password: "
      read -r password
      echo -n "Please enter your SQL Hostname (leave blank for default kosmoss): "
      read -r database

      # Setting default values for the hostname and database.
      if [ "$hostname" == '' ]; then hostname='127.0.0.1'; fi
      if [ "$database" == '' ]; then database='kosmoss'; fi

      # Writing the values into the .env file.
      echo "Writing values to the .env file..."
      {
        echo "SQL_HOSTNAME=$hostname"
        echo "SQL_USERNAME=$username"
        echo "SQL_PASSWORD=$password"
        echo "SQL_DATABASE=$database"
      } >> /opt/bitnami/kosmoss/.env
    fi
  else
    echo "Existing .env file found, skipping .env creation."
  fi

	# Restarting services.
	echo "Restarting apache service..."
	sudo /opt/bitnami/ctlscript.sh restart apache

	echo "Kosmoss core files downloaded!"
	echo "To install Composer dependencies and finish installation, please run the following commands:"
	echo "cd /opt/bitnami/kosmoss"
	echo "composer install"
else
	echo "This installer is meant for Bitnami-hosted LAMP servers."
	echo "Please run the appropriate installer for your server platform."
 	echo "Terminating installation..."
fi
