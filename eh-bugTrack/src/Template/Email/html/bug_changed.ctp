<?php
declare(strict_types = 1);
/**
 * Шаблон уведомления о изменении задачи (бага)
 *
 * @var Task $task
 */

use App\Model\Entity\Task;

echo '<h1>Changed Bug №'.$task->id.'</h1>
<hr>
<table>
    <tr>
        <th>Наименование: </th>
        <td>'.h($task->name).'</td>
        <th>Тип задачи: </th>
        <td>'.$task->type_name.'</td>
        <th>Статус: </th>
        <td>'.$task->status_name.'</td>
    </tr>
</table>
<table>
    <tr>
        <th>Описание</th>
    </tr>
    <tr>
        <td>'.h($task->content).'</td>
    </tr>
    <tr>
        <th>Комментариц</th>
    </tr>
    <tr>
        <td>'.h($task->comment).'</td>
    </tr>
</table>';
