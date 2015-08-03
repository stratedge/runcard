#!/bin/bash

export DEBIAN_FRONTEND=noninteractive

echo "--------------------------------------------------"
echo "|             BEGINNING PROVISIONING             |"
echo "--------------------------------------------------"

echo "# bash_profile"
echo "--------------------------------------------------"

# Copy the .bash_profile
echo "  Setting up the vagrant user"
cp /vagrant/provision/vagrant/.bash_profile .bash_profile

echo "--------------------------------------------------"
echo "# add-apt-repository"
echo "--------------------------------------------------"

# Install add-apt-repository
echo "  Checking if add-apt-repository is availabe"
command -v add-apt-repository > /dev/null
if [[ $? != 0 ]]
then
	echo "  Installing add-apt-repository"
	apt-get install python-software-properties
else
	echo "  add-apt-repository already installed"
fi

echo "--------------------------------------------------"
echo "# apt-get update"
echo "--------------------------------------------------"

# Update apt-get repos
echo "  Updating apt-get repositories"
#apt-get update > /dev/null

echo "--------------------------------------------------"
echo "# php5"
echo "--------------------------------------------------"

# Install PHP
echo "  Checking for php5"
dpkg -l php5 > /dev/null 2>&1
if [[ $? != 0 ]]
then
	echo "  Installing php5"

	# Get the PHP 5.6 library
	echo "    Adding ondrej/php5-5.6 repository"
	add-apt-repository ppa:ondrej/php5-5.6 > /dev/null 2>&1

	# Update apt-get repos
	echo "    Updating apt-get repositories"
	apt-get update > /dev/null
	
	# Install PHP
	echo "    Installing php5"
	apt-get install -y -qq php5 > /dev/null 2>&1
else
	echo "  php5 already installed"
fi

echo "--------------------------------------------------"
echo "# php5-cli"
echo "--------------------------------------------------"

# Install php-cli
echo "  Checking for php5-cli"
dpkg -l php5-cli > /dev/null 2>&1
if [[ $? != 0 ]]
then
	echo "  Installing php5-cli"
	apt-get install -y -qq php5-cli 2> /dev/null
else
	echo "  php5-cli already installed"
fi

# Do everything else in PHP
php /vagrant/provision/provision.php

echo "--------------------------------------------------"
echo "|             PROVISIONING COMPLETED             |"
echo "--------------------------------------------------"