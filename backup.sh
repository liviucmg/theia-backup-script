#!/bin/bash 

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Create MySQL backup.
$DIR/mysql/backup.sh

# Create PostgreSQL backup.
$DIR/postgresql/backup.sh

# Create other files backup.
php -f "$DIR/files/backup.php"
