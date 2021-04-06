<?php
declare(strict_types = 1);
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var array $types //массив со списком типов задач
 * @var array $users //массив со списком пользователей
 * @var array $statuses //массив со списком статусов
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
<h1><?= $task->name ?> - редактирование</h1>
<div class="row">
    <div class="col-sm-12">
        <?= $this->Form->create($task) ?>
        <fieldset>
            <?php
            echo $this->Form->control('name');
            echo $this->Form->control('status', ['options' => $statuses]);
            echo $this->Form->control('content');
            echo $this->Form->control('comment');
            echo $this->Form->control('bug_type', ['options' => $types]);
            echo $this->Form->control('worker', ['options' => array_merge([0 => 'не выбран'],$users)]);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
