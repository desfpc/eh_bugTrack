<?php
declare(strict_types = 1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Entity\Task;
use App\Model\Table\TasksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TasksTable Test Case
 */
class TasksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TasksTable
     */
    public $Tasks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Tasks',
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
        $config = TableRegistry::getTableLocator()->exists('Tasks') ? [] : ['className' => TasksTable::class];
        $this->Tasks = TableRegistry::getTableLocator()->get('Tasks', $config);
    }

    /**
     * May Edit method
     *
     */
    public function testMayEdit()
    {
        $task = $this->Tasks->get(1);
        $this->assertEquals(true, $task->mayEdit(1)); //задачу может править автор и исполнитель
        $this->assertEquals(false, $task->mayEdit(2)); //задачу не может править другой пользователь

        $task = $this->Tasks->get(3);
        $this->assertEquals(true, $task->mayEdit(1)); //задачу может править автор
        $this->assertEquals(true, $task->mayEdit(2)); //задачу может править исполнитель
    }

    /**
     * May Delete method
     *
     */
    public function testMayDelete()
    {
        $task = $this->Tasks->get(1);
        $this->assertEquals(true, $task->mayDelete(1)); //задачу может удалить автор
        $this->assertEquals(false, $task->mayDelete(2)); //задачу не может удалить другой пользователь

        $task = $this->Tasks->get(3);
        $this->assertEquals(true, $task->mayDelete(1)); //задачу может удалить автор
        $this->assertEquals(false, $task->mayDelete(2)); //задачу не может удалить исполнитель
    }

    /**
     *
     * Find All Tasks rows
     *
     */
    public function testFindAll()
    {
        //$query = $this->Tasks->find('all');
        $query = $this->Tasks->find()->select(['id', 'name']);
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            ['id' => 1, 'name' => 'Lorem ipsum dolor sit amet'],
            ['id' => 2, 'name' => 'Lorem ipsum dolor sit amet 2'],
            ['id' => 3, 'name' => '3-ая тестовая задача']
        ];

        $this->assertEquals($expected, $result);
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
            'id' => 4,
            'name' => 'Lorem ipsum dolor sit amet',
            'status' => 'created',
            'date_created' => '2021-03-29 10:53:48',
            'date_updated' => '2021-03-29 10:53:48',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'comment' => '',
            'bug_type' => 'bug',
            'author' => 1,
            'worker' => 1,
        ];
        $task = $this->Tasks->newEntity($testData);
        $err = $task->getErrors();
        $this->assertEquals(0, count($err));

        //проверка не валидных данных
        $testData = [
            'id' => 5,
            'name' => '1234', //короткое название
            'status' => 'test_created', //не верный статус
            'date_created' => 'azaza', //не верная строка даты
            'date_updated' => '2021-03-29 10:53:48',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'comment' => '',
            'bug_type' => 'test_bug', //не верный тип задачи
            'author' => 1,
            'worker' => 'Вася', //автор - строка
        ];
        $task = $this->Tasks->newEntity($testData);
        $err = $task->getErrors();
        $this->assertEquals(5, count($err));
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tasks);

        parent::tearDown();
    }
}
