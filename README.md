# PHP Backup Utility

#### What does it can?

* Backup websites and other files on server
* Backup database

## Usage

```php
$backup = new Backup;

$exclude = array( // Define directories to exclude
    'unnecessary _dir',
    'example.com'
);

$backup->set_backup_dir('/home/www/', $exclude);  // Define path to the directory that needs to be backup
$backup->set_backup_storage('/home/www/backup/'); // Define path to the directory where backups will be stored
$backup->db_credentials('localhost', 'root', 'pass'); // Your database credentials

$backup->run(); // run backup
```