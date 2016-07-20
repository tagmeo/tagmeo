#!/usr/bin/env bash

ENV_FILE=/var/www/tagmeo/.env

if [ -f $ENV_FILE ]; then
    DB_NAME=`grep DB_NAME $ENV_FILE | cut -d "=" -f 2`

    if [ ! -d /var/lib/mysql/$DB_NAME ]; then
        echo ">>> Creating Database"

        DB_USER=`grep DB_USER $ENV_FILE | cut -d "=" -f 2`
        DB_PASS=`grep DB_PASS $ENV_FILE | cut -d "=" -f 2`

        mysql --user="$DB_USER" --password="$DB_PASS" -e "CREATE DATABASE $DB_NAME"
    fi
fi
