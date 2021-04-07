<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Model\Entity\Task;
use App\Model\Table\TasksTable;
use App\Model\Table\UsersTable;
use Cake\Cache\Cache;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Response;
use Cake\Mailer\Email;

/**
 * Tasks Controller
 *
 * @property TasksTable $Tasks
 * @property UsersTable $Users
 *
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TasksController extends AppController
{
    /**
     * Настройки пейджинации по-умолчанию
     */
    public $paginate = [
        'limit' => 10,
        'order' => [
            'Tasks.bug_type' => 'asc',
            'Tasks.date_created' => 'desc'
        ]
    ];

    /**
     * isAuthorized method
     * Права на просмотр и создание задач для всех пользователей
     * Права на изменение записи - автор и исполнитель задачи
     * Право на удаление задачи - автор
     *
     *
     * @param array $user
     * @return bool|Response
     */
    public function isAuthorized(array $user)
    {

        if(!$user['id']) {
            return false;
        }

        $action = $this->request->getParam('action');

        //
        switch ($action) {
            // Экшены add и index разрешены для авторизированного пользователя
            case 'index':
            case 'add':
            case 'view':
                return true;

            // Экшен edit может редактировать автор и исполнитель задачи
            case 'edit':
                //получение id бага для редактирования
                $id = $this->request->getParam('pass.0');
                $editedTask = $this->Tasks->get($id);
                if($editedTask->mayEdit($user['id'])){
                    return true;
                }
                return false;

            // Экшен delete доступен только автору
            case 'delete':
                $id = $this->request->getParam('pass.0');
                $deletedTask = $this->Tasks->get($id);
                if($deletedTask->mayDelete($user['id'])){
                    return true;
                }
                return false;
        }

        return false;
    }

    /**
     * Index method
     *
     * @return Response|null
     */
    public function index()
    {

        //ID авторизованного пользователя
        $uid = $this->Auth->user('id');

        //Статусы задач
        $statuses = Task::getStatuses();

        //Типы задач
        $types = Task::getTypes();

        //Типы принадлежности задачи к пользователю
        $owners = ['author','worker'];

        //получение параметров для фильтра списка задач
        $type = $this->request->getQuery('type'); //тип бага
        if(!key_exists($type, $types)){
            $type = '';
        }
        $status = $this->request->getQuery('status'); //статус бага
        if(!key_exists($status, $statuses)){
            $status = '';
        }
        $owner = $this->request->getQuery('owner'); //задача по отношению к пользователю (all, author, worker)
        if(!in_array($owner, $owners)){
            $owner = '';
        }

        //список conditions для формирования списка задач
        if($type != ''){
            $this->paginate['conditions']['Tasks.bug_type'] = $type;
        }
        if($status != ''){
            $this->paginate['conditions']['Tasks.status'] = $status;
        }
        if($owner != ''){
            $this->paginate['conditions']['Tasks.'.$owner] = $uid;
        }

        //получение списка задач
        $tasks = $this->paginate($this->Tasks->find('all')->contain(['Authors','Workers']));

        //передаем переменные в view
        $this->set(compact('tasks','uid', 'owner', 'statuses', 'types', 'status', 'type'));

    }

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return Response|null
     * @throws RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        //ID авторизованного пользователя
        $uid = $this->Auth->user('id');

        //Статусы задач
        $statuses = Task::getStatuses();

        //Типы задач
        $types = Task::getTypes();

        //Читаем задачу из кэша
        $task = Cache::read('task_view_'.$id, 'redis');
        if ($task === false) {
            //Если нет кэша - берем из БД и сохраняем в кэш
            $task = $this->Tasks->get($id, [
                'contain' => [],
            ]);
            Cache::write('task_view_'.$id, $task, 'redis');
        }

        $task = $this->Tasks->get($id, [
            'contain' => ['Authors','Workers'],
        ]);

        $this->set(compact('task', 'types', 'statuses', 'uid'));

    }

    /**
     * Add method
     *
     * @return Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        //Пользователи
        $this->loadModel('Users');
        $users = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->toArray();

        //Типы задач
        $types = Task::getTypes();

        $task = $this->Tasks->newEntity();
        if ($this->request->is('post')) {

            //заполняем параметры новой Task
            $task = $this->Tasks->patchEntity($task, $this->request->getData(), ['associated' => []]);
            $task->author = $this->Auth->user('id'); //Автор - текущий пользователь
            $task->status = 'created';

            //сохранение задачи
            if ($this->Tasks->save($task)) {

                $this->log(json_encode(['action' => 'Added New Task', 'data' => $this->request->getData()]), 'debug');
                $this->Flash->success(__('The task has been saved.'));

                //отправляем уведомление
                $this->sendBugEmail(null, $task);

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }
        $this->set(compact('task', 'types', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return Response|null Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {

        //Статусы задач
        $statuses = Task::getStatuses();

        //Пользователи
        $this->loadModel('Users');
        $users = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'
        ])->toArray();

        //Типы задач
        $types = Task::getTypes();

        //читаем задачу из кэша
        $task = Cache::read('task_'.$id, 'redis');
        if ($task === false) {
            //если нет в кэше задачи, получаем ее из БД и заносим в кэш
            $task = $this->Tasks->get($id, [
                'contain' => [],
            ]);
            Cache::write('task_'.$id, $task, 'redis');
        }


        if ($this->request->is(['patch', 'post', 'put'])) {

            //старые данные для отсылки уведомлений
            $oldWorker = $task->worker;

            //заносим обновленные данные
            $task = $this->Tasks->patchEntity($task, $this->request->getData(), ['associated' => []]);

            //сохранение изменений
            if ($this->Tasks->save($task)) {

                $this->log(json_encode(['action' => 'Edited Task', 'data' => $this->request->getData()]), 'debug');
                $this->Flash->success(__('The task has been saved.'));

                //отправляем уведомление
                $this->sendBugEmail($oldWorker, $task);

                return $this->redirect(['action' => 'view', $task->id]);
            }

            $this->Flash->error(__('The task could not be saved. Please, try again.'));
        }
        $this->set(compact('task', 'types', 'users', 'statuses'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);
        if ($this->Tasks->delete($task)) {
            $this->Flash->success(__('The task has been deleted.'));
        } else {
            $this->Flash->error(__('The task could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Отсылка уведомления об изменении задачи (бага) TODO протестировать отсылку с email шаблоном
     *
     * @param null|int $oldWorker
     * @param Task $task
     * @return bool
     */
    private function sendBugEmail($oldWorker, Task $task): bool
    {
        //проверка на необходимость отсылки уведомления
        $sendNotice = false;
        $to = [];

        //если исполнитель поменялся; или редактор - автор и есть исполнитель; или редактор не автор - нужно отослать уведомление
        if (
            $oldWorker !== $task->worker ||
            ($this->Auth->user('id') === $task->author && !is_null($task->worker)) ||
            $this->Auth->user('id') !== $task->author
        ) {
            $sendNotice = true;

            //если текущий пользователь - автор
            if(!is_null($task->worker) && $this->Auth->user('id') === $task->author){
                $to[] = $task->worker; //отсылаем исполнителю (изменил автор)
            }
            elseif(!is_null($task->worker)) {
                $to[] = $task->author; //отсылаем автору (изменил исполнитель)
            }
            if(!is_null($oldWorker) && $task->worker !== $oldWorker){
                if(!in_array($oldWorker, $to)){
                    $to[] = $oldWorker; //отсылаем предыдущему исполнителю тоже (если он не попал в список уведомления ранее)
                }
            }
        }

        if($sendNotice){

            //отсылка уведомлений всем нуждающимся
            if(is_array($to) && count($to) > 0){
                foreach ($to as $id){
                    if(!is_null($id)){
                        $user = $this->Users->get($id, [
                            'contain' => [],
                        ]);

                        if(isset($user->login) && $user->login != ''){
                            //отсылаем уведомление
                            $email = new Email('default');
                            $email->setFrom(['peshalov.sergey@yandex.ru' => 'EH BugTracker'])
                                ->setTo($user->login, $user->name)
                                ->setSubject('Changed Bug №'.$task->id.': '.h($task->name).' - '.$task->status_name)
                                ->setEmailFormat('html')
                                ->viewBuilder()->setTemplate('bug_changed');
                            $email->setViewVars(['task' => $task])
                                ->send();
                        }
                    }
                }
            }
        }

        return true;

    }
}
