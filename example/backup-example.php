<?php
require_once "../vendor/autoload.php";
# require_once "../src/backup.php"; To load class directly

$backup = new \PHPBackup\Backup();

$exclude = array(
	'/bin/',
	'/var/www/example.com'
);

$backup->exclude_dirs($exclude);
$backup->set_backup_dir('/var/www/');  // Define path to the directory that needs to be backup
$backup->set_backup_storage('/home/backup/'); // Define path to the directory where backups will be stored
$backup->db_credentials('localhost', 'root', 'pass'); // Your database credentials

$backup->run(); // run backup