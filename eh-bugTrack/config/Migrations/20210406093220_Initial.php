<?php
declare(strict_types = 1);

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {

        $this->table('tasks')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('status', 'enum', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'values' => ['created','inwork','completed','canceled'],
            ])
            ->addColumn('date_created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('date_updated', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('comment', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('bug_type', 'enum', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'values' => ['critical','bug','improvement'],
            ])
            ->addColumn('author', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('worker', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'author',
                ]
            )
            ->addIndex(
                [
                    'worker',
                ]
            )
            ->addIndex(
                [
                    'status',
                ]
            )
            ->create();

        $this->table('users')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('login', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('pass', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addIndex(
                [
                    'login',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('tasks')
            ->addForeignKey(
                'author',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->addForeignKey(
                'worker',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('tasks')
            ->dropForeignKey(
                'author'
            )
            ->dropForeignKey(
                'worker'
            )->save();

        $this->table('tasks')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
