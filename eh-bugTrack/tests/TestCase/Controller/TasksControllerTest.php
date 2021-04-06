<?php
declare(strict_types = 1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\TasksController Test Case
 *
 * @uses \App\Controller\TasksController
 */
class TasksControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
        'app.Tasks',
    ];

    /**
     * Test index method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testIndex()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);
        $this->get('/tasks');

        $this->assertResponseOk();
        $this->assertResponseContains('<h1>Задачи</h1>');
        $this->assertResponseContains('Lorem ipsum dolor sit amet 2');
    }

    /**
     * Test view method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testView()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);
        $this->get('/tasks/view/2');

        $this->assertResponseOk();
        $this->assertResponseContains('Lorem ipsum dolor sit amet 2');

    }

    /**
     * Test add method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAdd()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);
        $this->get('/tasks/add');

        $this->assertResponseOk();
        $this->assertResponseContains('<h1>Создать задачу</h1>');
    }

    /**
     * testEditWithWrongRights method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testEditWithWrongRights()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);
        $this->get('/tasks/edit/2');
        $this->assertRedirect();
    }

    /**
     * test Edit method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testEdit()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);
        $this->get('/tasks/edit/1');
        $this->assertResponseOk();
    }

    /**
     * test Add Post method
     *
     * @throws \PHPUnit\Exception
     */
    public function testAddData()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);

        $data = [
            'name' => 'New Added Task Name',
            'content' => 'New Task Content',
            'bug_type' => 'critical',
            'comment' => '',
            'worker' => '0'
        ];

        $this->enableCsrfToken();
        $this->post('/tasks/add', $data);

        $tasks = TableRegistry::getTableLocator()->get('Tasks');
        $query = $tasks->find()->where([
            'name' => $data['name'],
            'content' => $data['content'],
            'bug_type' => $data['bug_type'],
            'author' => 1]);
        $this->assertEquals(1, $query->count());

    }

    /**
     * test Edit Post method
     *
     * @throws \PHPUnit\Exception
     */
    public function testEditData()
    {

        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);

        $data = [
            'name' => 'New Task Name',
            'worker' => 2
        ];

        $this->enableCsrfToken();

        $this->post('/tasks/edit/1', $data);

        $this->assertResponseSuccess();
        $tasks = TableRegistry::getTableLocator()->get('Tasks');
        $query = $tasks->find()->where(['name' => $data['name']]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * Test delete method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testDelete()
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);

        $this->enableCsrfToken();

        $this->post('/tasks/delete/1');
        $this->assertRedirect();

        $tasks = TableRegistry::getTableLocator()->get('Tasks');
        $query = $tasks->find()->where(['id' => 1]);
        $this->assertEquals(0, $query->count());

    }
}
