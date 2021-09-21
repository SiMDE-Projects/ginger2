#!/bin/bash

DUMP_TEMPFILE=/tmp/ginger.sql

echo "Loading $DUMP_TEMPFILE"
mysql -h localhost -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < $DUMP_TEMPFILE 

echo "Removing $DUMP_TEMPFILE"
rm $DUMP_TEMPFILE
