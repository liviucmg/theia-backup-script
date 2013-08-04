#!/bin/bash 

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "Backing up all PostgreSQL databases.\n";

rm -r $DIR/temp
mkdir $DIR/temp

mkdir $DIR/temp/postgresql 
sudo -i -u postgres pg_dumpall > $DIR/temp/dumpall.sql