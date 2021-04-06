<?php
declare(strict_types = 1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * @uses \App\Controller\PagesController
 */
class PagesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
        'app.Tasks'
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
        $this->get('/');

        $this->assertResponseOk();
        $this->assertResponseContains('<h1>Дашборд</h1>');
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }
}
