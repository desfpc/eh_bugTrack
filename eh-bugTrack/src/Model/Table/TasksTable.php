<?php
declare(strict_types = 1);

namespace App\Model\Table;

use App\Model\Entity\Task;
use ArrayObject;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tasks Model
 *
 * @method \App\Model\Entity\Task get($primaryKey, $options = [])
 * @method \App\Model\Entity\Task newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Task[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Task|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Task saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Task patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Task[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Task findOrCreate($search, callable $callback = null, $options = [])
 */
class TasksTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('tasks');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Authors', ['foreignKey' => 'author', 'className' => 'Users', 'propertyName' => 'author']);
        $this->belongsTo('Workers', ['foreignKey' => 'worker', 'className' => 'Users', 'propertyName' => 'worker']);
    }

    /**
     * Действия перед сохранением записи
     *
     * @param Event $event
     * @param Task $entity
     * @param array $options
     */
    public function beforeSave(Event $event, Task $entity, $options = [])
    {
        $entity->date_updated = FrozenTime::now();
    }

    /**
     * Действия после сохранения записи
     *
     * @param Event $event
     * @param Task $entity
     * @param array $options
     */
    public function afterSave(Event $event, Task $entity, $options = [])
    {
        //удваление кэшей
        Cache::delete('task_'.$entity->id, 'redis');
        Cache::delete('task_view_'.$entity->id, 'redis');
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->minLength('name', 5)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->add('status', 'validStatus', [
                'rule' => 'isValidStatus',
                'message' => __('You need to provide a valid status'),
                'provider' => 'table',
            ]);

        $validator
            ->dateTime('date_created')
            ->notEmptyDateTime('date_created');

        $validator
            ->dateTime('date_updated')
            ->allowEmptyDateTime('date_updated');

        $validator
            ->scalar('content')
            ->allowEmptyString('content');

        $validator
            ->scalar('comment')
            ->allowEmptyString('comment');

        $validator
            ->scalar('bug_type')
            ->requirePresence('bug_type', 'create')
            ->notEmptyString('bug_type')
            ->add('bug_type', 'validBugType', [
                'rule' => 'isValidType',
                'message' => __('You need to provide a valid bug type'),
                'provider' => 'table',
            ]);

        $validator
            ->integer('author')
            ->requirePresence('author', 'create')
            ->notEmptyString('author');

        $validator
            ->integer('worker')
            ->allowEmptyString('worker');

        return $validator;
    }

    /**
     * Валидный статус
     *
     * @param string $value
     * @param array $context
     * @return bool
     */
    public function isValidStatus(string $value, array $context): bool
    {
        return key_exists($value, Task::getStatuses());
    }

    /**
     * Валидный тип задачи
     *
     * @param string $value
     * @param array $context
     * @return bool
     */
    public function isValidType(string $value, array $context): bool
    {
        return key_exists($value, Task::getTypes());
    }
}
