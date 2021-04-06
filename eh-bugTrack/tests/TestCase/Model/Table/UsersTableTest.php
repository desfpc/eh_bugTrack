<?php
declare(strict_types = 1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = TableRegistry::getTableLocator()->get('Users', $config);
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {

        //проверка валидных данных
        $testData = [
            'id' => 3,
            'name' => 'Вася Пупкин',
            'login' => 'test1@user.com',
            'pass' => '$2y$10$LA4Ako8WsosFFj1FzW4VBO6sXbtm1W00McyX4wqGwzJJJkb3FEHt.',
        ];
        $user = $this->Users->newEntity($testData);
        $err = $user->getErrors();

        $this->assertEquals(0, count($err));

        //проверка не валидных данных
        $testData = [
            'id' => 4,
            'name' => 'Вася Пупкин',
            'login' => 'testuser.com ', //не верный email
            'pass' => '123', //короткий пароль
        ];
        $user = $this->Users->newEntity($testData);
        $err = $user->getErrors();
        $this->assertEquals(2, count($err));


    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }
}
