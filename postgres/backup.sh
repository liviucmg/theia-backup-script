#!/bin/bash 

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo -e "Backing up all PostgreSQL databases.\n";

mkdir -p $DIR/temp

sudo -u postgres pg_dumpall > $DIR/temp/dumpall.sql