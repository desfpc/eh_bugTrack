<?php
declare(strict_types = 1);

namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Exception;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $name
 * @property string $status
 * @property FrozenTime $date_created
 * @property FrozenTime|null $date_updated
 * @property string|null $content
 * @property string|null $comment
 * @property string $bug_type
 * @property int $author
 * @property int|null $worker
 */
class Task extends Entity
{

    /**
     * Проверка на возможность изменения записи пользователем с id $userId
     *
     * @param int $userId
     * @return bool
     */
    public function mayEdit(int $userId):bool
    {

        //можно изменять автору
        if(is_object($this->author)){
            if($this->author->id === $userId){
                return true;
            }
        }
        elseif($this->author === $userId){
            return true;
        }

        //можно изменять исполнителю
        if(is_object($this->worker)){
            if($this->worker->id === $userId){
                return true;
            }
        }
        elseif($this->worker === $userId){
            return true;
        }

        return false;

    }

    /**
     * Проверка на возможность удаления записи пользователем с id $userId
     *
     * @param int $userId
     * @return bool
     */
    public function mayDelete(int $userId): bool
    {

        if(is_object($this->author)){
            if($this->author->id === $userId){
                return true;
            }
        }
        elseif($this->author === $userId){
            return true;
        }

        return false;
    }

    /**
     * Получение возможных типов задачи ('critical','bug','improvement')
     *
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            'critical' => __('Critical Bug'),//'Срочный баг',
            'bug' => __('Minor Bug'),//'Несрочный баг',
            'improvement' => __('Minor Improvement')//'Незначительное улучшение'
        ];
    }

    /**
     * Virtual Field Имя типа бага ($task->type_name)
     *
     * @return string
     * @throws Exception
     */
    protected function _getTypeName(): string
    {
        if(!isset(Task::getTypes()[$this->bug_type])){
            throw new Exception('No bug type name');
        }

        return Task::getTypes()[$this->bug_type];
    }

    /**
     * Получение возможных статусов ('created','inwork','completed','canceled')
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            'created' => __('Created'),//'Создана',
            'inwork' => __('In work'),//'В работе',
            'completed' => __('Completed'),//'Выполнена',
            'canceled' => __('Canceled')//'Отменена'
        ];
    }

    /**
     * Virtual Field Имя статуса ($task->status_name)
     *
     * @return string|null
     * @throws Exception
     */
    protected function _getStatusName(): string
    {

        if(!isset(Task::getStatuses()[$this->status])){
            throw new Exception('No bug type name');
        }

        return __(Task::getStatuses()[$this->status]);
    }

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'status' => true,
        'date_created' => true,
        'date_updated' => true,
        'content' => true,
        'comment' => true,
        'bug_type' => true,
        'author' => true,
        'worker' => true,
    ];
}
