#!/bin/bash

STRUCT_TEMPFILE=/tmp/structure.sql
DATA_TEMPFILE=/tmp/datas.sql

echo "Loading $DUMP_TEMPFILE"
mysql -h localhost -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < $STRUCT_TEMPFILE
mysql -h localhost -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < $DATA_TEMPFILE
