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

if test -f /tmp/selenium-${SELENIUM_PORT}.pid
then
    echo "Stopping Selenium on port ${SELENIUM_PORT}..."
    PID=`cat /tmp/selenium-${SELENIUM_PORT}.pid`
    kill -3 $PID
    if kill -9 $PID ;
        then
            sleep 2
            test -f /tmp/selenium-${SELENIUM_PORT}.pid && rm -f /tmp/selenium-${SELENIUM_PORT}.pid
        else
            echo "Selenium could not be stopped..."
        fi
else
    echo "Selenium is not running."
fi