# Theia Backup Script

I use this set of scripts to backup my website files, MySQL databases and PostgreSQL databases. It uses [Duplicity](http://duplicity.nongnu.org/) as the backend, so the files can be backed up virtually anywhere (FTP, Amazon S3, etc.). The scripts are run on the source server, sending the files to the destination server.

Dependencies: Bash, PHP 5.4, Duplicity, Flock.

The scripts are provided as-is under the MIT License with absolutely no warranty. You should read each script file to see what it actually does.

## Install dependencies on Ubuntu 13.10
This should do it:
```
apt-get install php5-cli duplicity python-paramiko python-boto flock
```

## Create an autobackup user
You shouldn't run this script as root. Instead, you should create a dedicated user with limited access. I'll name it "autobackup".
```
$ useradd -m -s /bin/bash autobackup
```

Now you should log in as "autobackup" and set up everything required for an unattended SSH access to the backup server: SSH keys, GPG keys, add the remote server fingerprint, etc..

A very good tutorial can be found here (see the "Create SSH and GPG Keys" chapter): https://www.digitalocean.com/community/articles/how-to-use-duplicity-with-gpg-to-securely-automate-backups-on-ubuntu

## Grant PostgreSQL access
The "autobackup" user needs to run "pg_dumpall" as the "postgresql" user. To allow this, you have to run `visudo` and add the following line:

```
autobackup ALL=(postgres) NOPASSWD: /usr/bin/pg_dumpall
```

## Set up backups
You should check out the `backup.example.json` files and create your own ones as `backup.json` in each corresponding folder (e.g. files, mysql, postgres).

Finally, you should set a crontab to run the main `backup.sh` file.