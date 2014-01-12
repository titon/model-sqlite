<?php
/**
 * @copyright   2010-2013, The Titon Project
 * @license     http://opensource.org/licenses/bsd-license.php
 * @link        http://titon.io
 */

namespace Titon\Db\Sqlite;

use Titon\Db\Data\AbstractMiscTest;
use Titon\Db\Entity;
use Titon\Db\Query;
use Titon\Test\Stub\Table\User;

/**
 * Test class for misc database functionality.
 */
class MiscTest extends AbstractMiscTest {

    /**
     * Test table creation and deletion.
     */
    public function testCreateDropTable() {
        $user = new User();

        $sql = sprintf("SELECT COUNT(name) FROM sqlite_master WHERE type = 'table' AND name = '%s';", $user->getTableName());

        $this->assertEquals(0, $user->getDriver()->query($sql)->count());

        $user->createTable();

        $this->assertEquals(1, $user->getDriver()->query($sql)->count());

        $user->query(Query::DROP_TABLE)->save();

        $this->assertEquals(0, $user->getDriver()->query($sql)->count());
    }

    /**
     * Test table truncation.
     */
    public function testTruncateTable() {
        $this->markTestSkipped('SQLite does not support the TRUNCATE statement');
    }

    /**
     * Test that sub-queries return results.
     */
    public function testSubQueries() {
        $this->loadFixtures(['Users', 'Profiles', 'Countries']);

        $user = new User();

        // SQLite does not support the ANY filter, so use IN instead
        $query = $user->select('id', 'country_id', 'username');
        $query->where('country_id', 'in', $query->subQuery('id')->from('countries'))->orderBy('id', 'asc');

        $this->assertEquals([
            new Entity(['id' => 1, 'country_id' => 1, 'username' => 'miles']),
            new Entity(['id' => 2, 'country_id' => 3, 'username' => 'batman']),
            new Entity(['id' => 3, 'country_id' => 2, 'username' => 'superman']),
            new Entity(['id' => 4, 'country_id' => 5, 'username' => 'spiderman']),
            new Entity(['id' => 5, 'country_id' => 4, 'username' => 'wolverine']),
        ], $query->fetchAll());

        // Single record
        $query = $user->select('id', 'country_id', 'username');
        $query->where('country_id', '=', $query->subQuery('id')->from('countries')->where('iso', 'USA'));

        $this->assertEquals([
            new Entity(['id' => 1, 'country_id' => 1, 'username' => 'miles'])
        ], $query->fetchAll());
    }

}