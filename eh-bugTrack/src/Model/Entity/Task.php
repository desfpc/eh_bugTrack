<?php
declare(strict_types = 1);

namespace App\Model\Entity;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $name
 * @property string $status
 * @property \Cake\I18n\FrozenTime $date_created
 * @property \Cake\I18n\FrozenTime|null $date_updated
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
    public function mayEdit($userId){

        //можно изменять автору
        if(is_object($this->author)){
            if($this->author->id == $userId){
                return true;
            }
        }
        elseif($this->author == $userId){
            return true;
        }

        //можно изменять исполнителю
        if(is_object($this->worker)){
            if($this->worker->id == $userId){
                return true;
            }
        }
        elseif($this->worker == $userId){
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
    public function mayDelete($userId){

        if(is_object($this->author)){
            if($this->author->id == $userId){
                return true;
            }
            return false;
        }
        elseif($this->author == $userId){
            return true;
        }

        return false;
    }

    /**
     * Получение возможных типов задачи ('critical','bug','improvement')
     *
     * @return array
     */
    public static function getTypes(){

        //вывод полей из БД
        /*$query = "SHOW COLUMNS FROM tasks WHERE field = 'bug_type'";

        $db = ConnectionManager::get('default');
        $results = $db->execute($query)->fetchAll('assoc');
        if(count($results) > 0){
            //формирование массива с данными
            $enum = $results[0];
            $enum = str_replace('enum(','',$enum);
            $enum = str_replace(')','',$enum);
            $enum = str_replace('\'','',$enum);
            $arr = explode(',',$enum);

            //назначение имен для отображения

        }*/

        //ручной вывод полей
        return [
            'critical' => __('Critical Bug'),//'Срочный баг',
            'bug' => __('Minor Bug'),//'Несрочный баг',
            'improvement' => __('Minor Improvement')//'Незначительное улучшение'
        ];
    }

    /**
     * Virtual Field Имя типа бага ($task->type_name)
     *
     * @return string|null
     */
    protected function _getTypeName(){
        return __(Task::getTypes()[$this->bug_type]);
    }

    /**
     * Получение возможных статусов ('created','inwork','completed','canceled')
     *
     * @return array
     */
    public static function getStatuses(){
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
     */
    protected function _getStatusName(){
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
