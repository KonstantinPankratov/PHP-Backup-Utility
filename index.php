<?php
    class Backup
    {
        /**
         * Backup class serves to backup websites and databases on a server
         *
         * @var string $websites_dir   contains path to the directory with websites (i.e. ../www/ or ../public-html/)
         * @var string $backup_storage contains path to the backup directory (you can define it yourself)
         *
         * @var string $DB_host database host
         * @var string $DB_user database user
         * @var string $DB_pass database pass
         *
         * @var string $date_format  contains current date in DateTime format (YYYY-mm-dd)
         * @var string $backup_name  file or archive name that will store backup data (backup_YYYY-mm-dd)
         * @var int    $storage_time contains number of days to store backup files
         */

        private $websites_dir = 'home/www/';
        private $backup_storage = 'home/backup/';

        private $DB_host = 'localhost';
        private $DB_user = 'root';
        private $DB_pass = '';

        protected $date_format = null;
        protected $backup_name = 'backup_';
        protected $storage_time  = 7;

        public function __construct()
        {
            $this->date_format = date('Y-m-d');
            $this->backup_name = $this->backup_name . $this->date_format;
        }

        public function run() {
            $this->db();
            $this->websites();
            $this->remove_old();
        }

        /**
         * Defines filename, creates backup of all databases and saves it to $filename.sql inside $backup_storage
         * Returns true if success
         */
        private function db ()
        {
            $filename = $this->backup_name .'.sql';
            $filepath = $this->backup_storage . $filename;

            $credential = '--user '. $this->DB_user .' --password="'. $this->DB_pass .'" --host '. $this->DB_host;

            exec('mysqldump '. $credential .'  --all-databases  > '. $filepath, $output, $response);

            if ($response == 0) return true;
        }

        /**
         * Defines archive, creates backup of all directories inside $websites_dir and saves it to $filename.tar.gz inside $backup_storage
         * Returns true if success
         */
        private function websites ()
        {
            $filename = $this->backup_name .'.tar.gz';
            $filepath = $this->backup_storage . $filename;

            exec('tar -cvf '. $filepath .' '. $this->websites_dir .'*', $output, $response);

            if ($response == 0) return true;
        }

        /**
         * Scans $backup_storage for old files and
         */
        private function remove_old()
        {
            $backup_files = scandir($this->backup_storage);

            foreach ($backup_files as $file)
            {
                if ($file == '.' || $file == '..') continue;
                $date_regex = '(\d{1,2}-\d{1,2}-\d{1,2})';

                preg_match($date_regex, $file, $date);

                $date = new DateTime($date[0]);
                $now = new DateTime();
                $diff = $date->diff($now)->format("%d");

                if ($diff > $this->storage_time) unlink($this->backup_storage . $file);
            }
        }

    }

    $backup = new Backup;
    $backup->run();

?>