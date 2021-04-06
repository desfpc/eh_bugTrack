<?php
declare(strict_types = 1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'name' => ['type' => 'string', 'length' => 40, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'login' => ['type' => 'string', 'length' => 40, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'pass' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'login' => ['type' => 'unique', 'columns' => ['login'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Пешалов Сергей',
                'login' => 'desfpc@gmail.com',
                'pass' => '$2y$10$2uC1S2E6iNl71uLcyB33Q.Sr7dH1Geoqz2DhMt/uIirtnflVAZ3Na',
            ],
            [
                'id' => 2,
                'name' => 'Тестовый пользователь',
                'login' => 'test@user.com',
                'pass' => '$2y$10$LA4Ako8WsosFFj1FzW4VBO6sXbtm1W00McyX4wqGwzJJJkb3FEHt.',
            ],
        ];
        parent::init();
    }
}
