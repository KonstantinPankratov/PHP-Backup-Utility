# PHP Backup Utility

#### What does it can?

* Backup websites and other files on server
* Bulk database backup

## Usage

```php
$backup = new Backup;

$exclude = array( // Define directories to exclude
    '/bin/',
    '/var/www/example.com'
);

$backup->exclude_dirs($exclude); // Set paths to exclude
$backup->set_backup_dir('/home/www/');  // Set path to the directory that needs to be backup
$backup->set_backup_storage('/home/www/backup/'); // Set path to the directory where backups will be stored
$backup->db_credentials('localhost', 'root', 'pass'); // Your database credentials

$backup->run(); // run backup
```