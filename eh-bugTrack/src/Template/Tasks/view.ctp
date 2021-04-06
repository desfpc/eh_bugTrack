<?php
declare(strict_types = 1);
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var array $types //массив со списком типов задач
 * @var array $statuses //массив со списком статусов
 * @var int $uid //ID текущего пользователя
 */

//хлебные крошки
$this->Breadcrumbs->add(
    [
        ['title' => 'Дашборд', 'url' => '/'],
        ['title' => 'Задачи', 'url' => ['action' => 'index']],
        ['title' => $task->name, 'options' => ['class' => 'active']]
    ]
);

?>
<?= $this->Breadcrumbs->render() ?>
<h1><?= $task->name ?></h1>
<?php if($task->mayEdit($uid)): ?>
    <?= $this->Html->link(
        'Изменить задачу',
        $this->Url->build([
            "controller" => "Tasks",
            "action" => "edit",
            $task->id]),
        ['class' => 'btn btn-success btn-block']) ?>
<hr/>
<?php endif; ?>
<div class="row">
    <div class="col-sm-12">
        <table class="vertical-table table table-dark">
            <tr>
                <th scope="row"><?= __('Name') ?></th>
                <td><?= h($task->name) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Status') ?></th>
                <td><?= $statuses[$task->status] ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Bug Type') ?></th>
                <td><?= $types[$task->bug_type] ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Id') ?></th>
                <td><?= $this->Number->format($task->id) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Author') ?></th>
                <td><?= h($task->author->name) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Worker') ?></th>
                <td><?= isset($task->worker->name) ? h($task->worker->name) : 'не назначено' ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Date Created') ?></th>
                <td><?= h($task->date_created) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Date Updated') ?></th>
                <td><?= h($task->date_updated) ?></td>
            </tr>
        </table>
        <table class="table table-dark">
            <tr>
                <th><?= __('Content') ?></th>
            </tr>
            <tr>
                <td><?= $this->Text->autoParagraph(h($task->content)); ?></td>
            </tr>
        </table>
        <table class="table table-dark">
            <tr>
                <th><?= __('Comment') ?></th>
            </tr>
            <tr>
                <td><?= $this->Text->autoParagraph(h($task->comment)); ?></td>
            </tr>
        </table>
    </div>
</div>
