#!/bin/bash 

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

(

        # Do not allow more than one instance of this script.
        flock -x -n 200 || exit 1

        # Create MySQL backup.
        php -f "$DIR/mysql/backup.php"

        # Create PostgreSQL backup.
        $DIR/postgres/backup.sh

        # Backup everything to S3.
        php -f "$DIR/files/backup.php"

) 200>${DIR}/backup.lock
