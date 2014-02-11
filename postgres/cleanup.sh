#!/bin/bash 

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo -e "Cleaning up PostgreSQL backup.\n";

rm -r $DIR/temp