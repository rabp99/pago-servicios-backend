<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PagosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PagosTable Test Case
 */
class PagosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PagosTable
     */
    public $Pagos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pagos',
        'app.servicios'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Pagos') ? [] : ['className' => PagosTable::class];
        $this->Pagos = TableRegistry::get('Pagos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pagos);

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
