<?php

class Backup
{
    /**
     * Backup class serves to backup websites and databases on a server
     *
     * @var string $backup_dir     contains path to the directory that needs to be backup
     * @var string $backup_storage contains path to the directory that will store backup files
     *
     * @var string $DB_host database host
     * @var string $DB_user database user
     * @var string $DB_pass database pass
     *
     * @var string $date_format     contains current date in DateTime format (YYYY-mm-dd)
     * @var string $backup_prefix   file or archive name that will store backup data (backup_YYYY-mm-dd)
     * @var int    $days_of_storage contains number of days to store backup files
     */

    private $backup_dir = '';
    private $backup_storage = '';

    private $DB_host = 'localhost';
    private $DB_user = 'root';
    private $DB_pass = '';

    protected $date_format = null;
    protected $backup_prefix = 'backup_';
    protected $days_of_storage  = 7;

    public function __construct()
    {
        $this->date_format = date('Y-m-d');
        $this->backup_prefix = $this->backup_prefix . $this->date_format;
    }

    public function run() {
        try {
            $this->db();
            $this->check_paths();
            $this->websites();
            $this->remove_old();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Set path to the directory that needs to be backup
     *
     * @param string $path
     */
    public function set_backup_dir($path)
    {
        if ($path != '')
            $this->backup_dir = $path;
    }

    /**
     * Set path to the directory that will store backup files
     *
     * @param string $path
     */
    public function set_backup_storage($path)
    {
        if ($path != '')
            $this->backup_storage = $path;
    }

    /**
     * Check $backup_dir & $backup_storage
     *
     * @throws Exception if paths are not defined
     */
    private function check_paths()
    {
        if ($this->backup_dir == '')
            throw new \Exception("Define path to the directory that needs to be backup by calling method set_backup_dir('/your/path/'); before run();");

        if ($this->backup_storage == '')
            throw new \Exception("Define path to the directory that will store backup files by calling method set_backup_storage('/your/path/'); before run();");
    }

    /**
     * Set DataBase credentials
     *
     * @param string $host Contains database host
     * @param string $user Contains database user
     * @param string $pass Contains database password
     */
    public function db_credentials($host, $user, $pass)
    {
        $this->DB_host = $host;
        $this->DB_user = $user;
        $this->DB_pass = $pass;
    }

    /**
     * Defines filename, creates backup of all databases and saves it to $filename.sql inside $backup_storage
     *
     * @throws Exception if error while connection or creating db dump
     */
    private function db ()
    {
        $filename = $this->backup_prefix .'.sql';
        $filepath = $this->backup_storage . $filename;

        $credential = '--user '. $this->DB_user .' --password="'. $this->DB_pass .'" --host '. $this->DB_host;

        exec('mysqldump '. $credential .'  --all-databases  > '. $filepath, $output, $response);

        if ($response != 1)
            throw new \Exception("Cannot create files backup. Please, check your path in set_backup_dir() method.");
    }

    /**
     * Defines archive, creates backup of all directories inside $backup_dir and saves it to $filename.tar.gz inside $backup_storage
     *
     * @throws Exception if error while creating files backup
     */
    private function websites ()
    {
        $filename = $this->backup_prefix .'.tar.gz';
        $filepath = $this->backup_storage . $filename;

        exec('tar -cvf '. $filepath .' '. $this->backup_dir .'*', $output, $response);

        if ($response != 0)
            throw new \Exception("Cannot create files backup. Please, check your path in set_backup_dir() method.");
    }

    /**
     * Scans $backup_storage for old files and
     *
     * @throws Exception while missing directory or scan failure
     */
    private function remove_old()
    {
        if (is_dir($this->backup_storage)) {
            $backup_files = scandir($this->backup_storage);
        } else {
            throw new \Exception("Backup storage directory is missing or cannot be scanned. Please, check your path in set_backup_storage() method.");
        }

        foreach ($backup_files as $file)
        {
            if ($file == '.' || $file == '..') continue;
            $date_regex = '(\d{1,2}-\d{1,2}-\d{1,2})';

            preg_match($date_regex, $file, $date);

            $date = new DateTime($date[0]);
            $now = new DateTime();
            $diff = $date->diff($now)->format("%d");

            if ($diff > $this->days_of_storage)
                unlink($this->backup_storage . $file);
        }
    }

}

$backup = new Backup;
$backup->set_backup_dir('/home/www/');
$backup->set_backup_storage('/home/www/backup/');
$backup->db_credentials('localhost', 'root', '');
$backup->run();

?>