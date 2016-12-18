#!/bin/bash

set -e

scriptPath=$(cd `dirname $0`; pwd)
if [ $UID -ne 0 ]
then
	echo "Use \"sudo\" to run this, or switch to root to run this."
	exit 1
fi


sudo touch /var/log/install_gulp_server.log
sudo chmod 666 /var/log/install_gulp_server.log


section="# ----- [ Gulp ] ----- #"
echo "$section"

npm install -g gulp >> /var/log/install_gulp_server.log 2>&1
npm install gulp >> /var/log/install_gulp_server.log 2>&1
npm install jshint gulp-jshint gulp-sass gulp-concat gulp-uglify gulp-rename gulp-minify gulp-minify-css gulp-less >> /var/log/install_gulp_server.log 2>&1



