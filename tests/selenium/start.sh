#!/bin/bash

# Default parameters
SELENIUM_PORT=4444

while getopts ":p:" opt; do
  case $opt in
    p)
      SELENIUM_PORT=$OPTARG
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      echo "Usage: `basename ${BASH_SOURCE[0]}` -p PORT"
      ;;
  esac
done

if [ ! -f selenium-server-standalone.jar ]
then
    echo "selenium-server-standalone.jar not found. Downloading..."
    curl http://selenium-release.storage.googleapis.com/2.45/selenium-server-standalone-2.45.0.jar > selenium-server-standalone.jar
fi

if [ ! -f chromedriver ]
then
    # TODO: Support other OS
    echo "chromedriver not found. Downloading..."
    curl http://chromedriver.storage.googleapis.com/2.14/chromedriver_mac32.zip > chromedriver.zip
    unzip chromedriver.zip
    rm chromedriver.zip
fi


if test -f /tmp/selenium-${SELENIUM_PORT}.pid
then
    echo "Selenium is already running."
else
    [ -d /var/log/selenium ] || mkdir /var/log/selenium
    java -jar selenium-server-standalone.jar -port ${SELENIUM_PORT} -trustAllSSLCertificates \
    > /var/log/selenium/output-${SELENIUM_PORT}.log 2> /var/log/selenium/error-${SELENIUM_PORT}.log \
    & echo $! > /tmp/selenium-${SELENIUM_PORT}.pid
    echo ''
    echo "Starting Selenium on port ${SELENIUM_PORT}.. Run stop.sh to stop it"
    echo ''

    error=$?
    if test $error -gt 0
    then
        echo "Error $error! Couldn't start Selenium!"
    fi
fi
