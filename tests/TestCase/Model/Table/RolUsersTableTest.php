<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RolUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RolUsersTable Test Case
 */
class RolUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RolUsersTable
     */
    public $RolUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.rol_users',
        'app.users',
        'app.roles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('RolUsers') ? [] : ['className' => RolUsersTable::class];
        $this->RolUsers = TableRegistry::get('RolUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RolUsers);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
