# PHP Backup Utility

#### What does it can?

* Backup websites and other files on server
* Backup database

## Usage

```php
$backup = new Backup;

$backup->set_backup_dir('/home/www/');            // Define path what directory needs to be backup
$backup->set_backup_storage('/home/www/backup/'); // Define path where backups will be stored
$backup->db_credentials('localhost', 'root', ''); // Your database credentials

$backup->run(); // run backup
```