<?php
declare(strict_types = 1);
/**
 * @var \App\View\AppView $this
 * @var array $statuses //массив со списком статусов задач
 * @var array $types //массив со списком типов задач
 * @var array $tasks //массив со списком назначенных задач
 * @var array $createdTasks //массив со списком созданных задач
 * @var int $uid //ID текущего пользователя
 */

//хлебные крошки
$this->Breadcrumbs->add(
    [
        ['title' => 'Дашборд', 'options' => ['class' => 'active']]
    ]
);

$filtersArr['status'] = '';
$filtersArr['type'] = '';

?>
<?= $this->Breadcrumbs->render() ?>
<h1>Дашборд</h1>
<div class="row">
    <?= $this->element('sidebar', [
        'filtersArr' => $filtersArr,
        'owner' => '-1',
        'status' => '',
        'statuses' => $statuses,
        'type' => '',
        'types' => $types]);?>
    <div class="col-lg-10 tasks index large-9 medium-8 columns content">
        <h2>Назначенные задачи</h2>
        <table cellpadding="0" cellspacing="0" class="table table-dark table-striped table-responsive">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Задача</th>
                <th scope="col">Статус</th>
                <th scope="col">Создана</th>
                <th scope="col">Изменена</th>
                <th scope="col">Тип</th>
                <th scope="col">Автор</th>
                <th scope="col">Исполнитель</th>
                <th scope="col" class="actions"><?= __('Действия') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tasks as $task) { ?>
                <tr>
                    <td><?= $this->Number->format($task->id) ?></td>
                    <td><?= $this->Html->link($task->name, ['controller' => 'tasks', 'action' => 'view', $task->id]) ?></td>
                    <td><?= $task->status_name ?></td>
                    <td><?= h($task->date_created) ?></td>
                    <td><?= h($task->date_updated) ?></td>
                    <td><?= $task->type_name ?></td>
                    <td><?= $task->author->name ?></td>
                    <td><?= isset($task->worker->name) ? $task->worker->name : 'не назначено' ?></td>
                    <td class="actions">
                        <?= $task->mayEdit($uid) ? $this->Html->link(__('Edit'), ['controller' => 'tasks', 'action' => 'edit', $task->id]) : '' ?>
                        <?= $task->mayDelete($uid) ? $this->Form->postLink(__('Delete'), ['controller' => 'tasks', 'action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id)]) : '' ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <hr />
        <h2>Созданные задачи</h2>
        <table cellpadding="0" cellspacing="0" class="table table-dark table-striped table-responsive">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Задача</th>
                <th scope="col">Статус</th>
                <th scope="col">Создана</th>
                <th scope="col">Изменена</th>
                <th scope="col">Тип</th>
                <th scope="col">Автор</th>
                <th scope="col">Исполнитель</th>
                <th scope="col" class="actions"><?= __('Действия') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($createdTasks as $task) { ?>
                <tr>
                    <td><?= $this->Number->format($task->id) ?></td>
                    <td><?= $this->Html->link($task->name, ['controller' => 'tasks', 'action' => 'view', $task->id]) ?></td>
                    <td><?= $task->status_name ?></td>
                    <td><?= h($task->date_created) ?></td>
                    <td><?= h($task->date_updated) ?></td>
                    <td><?= $task->type_name ?></td>
                    <td><?= $task->author->name ?></td>
                    <td><?= isset($task->worker->name) ? $task->worker->name : 'не назначено' ?></td>
                    <td class="actions">
                        <?= $task->mayEdit($uid) ? $this->Html->link(__('Edit'), ['controller' => 'tasks', 'action' => 'edit', $task->id]) : '' ?>
                        <?= $task->mayDelete($uid) ? $this->Form->postLink(__('Delete'), ['controller' => 'tasks', 'action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id)]) : '' ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
