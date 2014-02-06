#!/bin/bash 

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

(

        # Do not allow more than one instance of this script.
        flock -x 200 || exit 1

        # Create MySQL backup.
        $DIR/mysql/backup.sh

        # Create PostgreSQL backup.
        $DIR/postgresql/backup.sh

        # Backup everything to S3.
        php -f "$DIR/files/backup.php"

) 200>${DIR}/backup.lock
