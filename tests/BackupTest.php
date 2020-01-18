<?php

use PHPUnit\Framework\TestCase;

class BackupTest extends TestCase
{

	private $backup;
	private $excludeDirs;

	protected function setUp(): void
	{
		$this->backup = new \PHPBackup\Backup();

		$this->excludeDirs = array(
			'/bin/',
			'/var/www/example.com'
		);

		$this->backup->exclude_dirs($this->excludeDirs);
	}

	protected function tearDown(): void
	{
		unset($this->backup);
		unset($this->excludeDirs);
	}

	public function testExcludeDirs()
	{
		$this->assertIsArray($this->backup->exclude_dirs);
		$this->assertEquals($this->backup->exclude_dirs, $this->excludeDirs);
	}

	public function testCredentials()
	{
		$this->assertIsArray($this->backup->db_credentials('host', 'user', 'pass'));
	}
}