<?php
declare(strict_types = 1);

namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * @uses \App\Controller\UsersController
 */
class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
    ];

    /**
     * testUserLoginPage method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testUserLoginPage()
    {
        $this->get('/users/login');
        $this->assertResponseOk();
        $this->assertResponseContains('Bug Tracker Lite');
        //$this->assertResponseContains('<html>');
    }

    /**
     * testAddUnauthenticatedFails method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAddUnauthenticatedFails()
    {
        // No session data set.
        $this->get('/users/add');

        $this->assertRedirect(['controller' => 'Users', 'action' => 'login', '?' => [
            'redirect' => '/users/add'
        ]]);
    }

    /**
     * testAddAuthenticated method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAddAuthenticated()
    {
        // Set session data
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'name' => 'Пешалов Сергей',
                    'login' => 'desfpc@gmail.com'
                ]
            ]
        ]);
        $this->get('/users/add');

        $this->assertResponseOk();
        // Other assertions.
    }

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
        $this->get('/');

        $this->assertResponseOk();
        $this->assertResponseContains('<h1>Дашборд</h1>');
    }
}
