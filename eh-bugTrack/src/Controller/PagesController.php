<?php
declare(strict_types = 1);
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\Task;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use \App\Model\Table\TasksTable;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 *
 * @property \App\Model\Table\TasksTable $Tasks
 *
 */
class PagesController extends AppController
{

    /**
     * isAuthorized method
     * Права на просмотр и создание задач для всех пользователей
     * Права на изменение записи - автор и исполнитель задачи
     *
     *
     * @param $user
     * @return bool
     */
    public function isAuthorized($user)
    {
        if($user['id']){
            return true;
        }
        return false;
    }

    public function home(){

        //ID авторизованного пользователя
        $uid = $this->Auth->user('id');

        //Статусы задач
        $statuses = Task::getStatuses();

        //Типы задач
        $types = Task::getTypes();

        $this->loadModel('Tasks');

        //Список задач в работе пользователя со статусами created, inwork
        $tasks = $this->Tasks->find('all')
            ->where([
                'status IN' => ['created','inwork'],
                'worker' => $uid
            ])
            ->contain(['Authors','Workers']);

        //Список созданных задач пользователя со статусами created, inwork
        $createdTasks = $this->Tasks->find('all')
            ->where([
                'status IN' => ['created','inwork'],
                'author' => $uid
            ])
            ->contain(['Authors','Workers']);


        $this->set(compact('uid','tasks', 'createdTasks','statuses', 'types'));

    }
}
