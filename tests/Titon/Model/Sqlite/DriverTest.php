<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Model\Sqlite;

use Titon\Common\Config;
use Titon\Test\Stub\Model\Stat;
use Titon\Test\Stub\Model\User;

/**
 * Test class for driver specific testing.
 */
class DriverTest extends \Titon\Model\Driver\PdoDriverTest {

	/**
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new SqliteDriver('default', Config::get('db'));
		$this->object->connect();

		$this->model = new User();
	}

	/**
	 * Test table inspecting.
	 */
	public function testDescribeTable() {
		$this->loadFixtures(['Users', 'Stats']);

		$user = new User();
		$this->assertEquals([
			'id' => [
				'field' => 'id',
				'type' => 'integer',
				'length' => '',
				'null' => false,
				'default' => '',
				'primary' => true,
				'ai' => true
			],
			'country_id' => [
				'field' => 'country_id',
				'type' => 'integer',
				'length' => '',
				'null' => true,
				'default' => null
			],
			'username' => [
				'field' => 'username',
				'type' => 'varchar',
				'length' => '255',
				'null' => true,
				'default' => null
			],
			'password' => [
				'field' => 'password',
				'type' => 'varchar',
				'length' => '255',
				'null' => true,
				'default' => null
			],
			'email' => [
				'field' => 'email',
				'type' => 'varchar',
				'length' => '255',
				'null' => true,
				'default' => null
			],
			'firstName' => [
				'field' => 'firstName',
				'type' => 'varchar',
				'length' => '255',
				'null' => true,
				'default' => null
			],
			'lastName' => [
				'field' => 'lastName',
				'type' => 'varchar',
				'length' => '255',
				'null' => true,
				'default' => null
			],
			'age' => [
				'field' => 'age',
				'type' => 'tinyint',
				'length' => '',
				'null' => true,
				'default' => null
			],
			'created' => [
				'field' => 'created',
				'type' => 'datetime',
				'length' => '',
				'null' => true,
				'default' => null
			],
			'modified' => [
				'field' => 'modified',
				'type' => 'datetime',
				'length' => '',
				'null' => true,
				'default' => null
			],
		], $user->getDriver()->describeTable($user->getTable()));

		$stat = new Stat();
		$this->assertEquals([
			'id' => [
				'field' => 'id',
				'type' => 'integer',
				'length' => '',
				'null' => false,
				'default' => null,
				'primary' => true,
				'ai' => true
			],
			'name' => [
				'field' => 'name',
				'type' => 'varchar',
				'length' => '255',
				'null' => true,
				'default' => null
			],
			'health' => [
				'field' => 'health',
				'type' => 'integer',
				'length' => '',
				'null' => true,
				'default' => null
			],
			'energy' => [
				'field' => 'energy',
				'type' => 'smallint',
				'length' => '',
				'null' => true,
				'default' => null
			],
			'damage' => [
				'field' => 'damage',
				'type' => 'float',
				'length' => '',
				'null' => true,
				'default' => null
			],
			'defense' => [
				'field' => 'defense',
				'type' => 'double',
				'length' => '',
				'null' => true,
				'default' => null
			],
			'range' => [
				'field' => 'range',
				'type' => 'decimal',
				'length' => '8,2',
				'null' => true,
				'default' => null
			],
			'isMelee' => [
				'field' => 'isMelee',
				'type' => 'boolean',
				'length' => '',
				'null' => true,
				'default' => null
			],
			'data' => [
				'field' => 'data',
				'type' => 'blob',
				'length' => '',
				'null' => true,
				'default' => null
			],
		], $user->getDriver()->describeTable($stat->getTable()));
	}

	/**
	 * Test DSN building.
	 */
	public function testGetDsn() {
		$this->assertEquals('sqlite::memory:', $this->object->getDsn());

		$this->object->config->memory = false;
		$this->assertEquals('sqlite:', $this->object->getDsn());

		$this->object->config->dsn = 'custom:dsn';
		$this->assertEquals('custom:dsn', $this->object->getDsn());
	}

}