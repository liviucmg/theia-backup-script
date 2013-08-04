#!/bin/bash 

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Create MySQL backup
$DIR/mysql/backup.sh

# Create PostgreSQL backup
$DIR/postgresql/backup.sh

# Backup everything to S3
php -f "$DIR/s3/backup.php"
