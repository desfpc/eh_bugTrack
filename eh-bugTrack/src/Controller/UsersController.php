<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
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

        $action = $this->request->getParam('action');

        if($action == 'login' && $user['id']){
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }

        if(!$user['id']) {
            return false;
        }

        switch ($action) {
            // Экшены add и index разрешены для авторизированного пользователя
            case 'index':
            case 'add':
            case 'view':
                return true;

            // Экшен edit - изменение доступно только самому пользователю
            case 'edit':
                //получение id пользователя для редактирования
                $id = $this->request->getParam('pass.0');
                $editedUser = $this->Users->get($id);

                if ($editedUser->id == $user['id']) {
                    return true;
                }
                break;
        }

        return false;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * Initialize method
     * Allow to User logout
     *
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout']);
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null
     */
    public function logout()
    {
        $this->Flash->success('Вы вышли из своей учетной записи.');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null
     */
    public function login()
    {

        if($this->Auth->isAuthorized()){
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Ваше имя пользователя или пароль не верны.');
        }
    }
}
