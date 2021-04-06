<?php
declare(strict_types = 1);
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task[]|\Cake\Collection\CollectionInterface $tasks //список задач
 * @var int $uid //ID текущего пользователя
 * @var string $owner //тип принадлежности задачи к пользователю
 * @var array $statuses //массив со списком статусов задач
 * @var array $types //массив со списком типов задач
 * @var string $status //выбранный статус задачи
 * @var string $type //выбранный тип задачи
 */

//хлебные крошки
$this->Breadcrumbs->add(
    [
        ['title' => 'Дашборд', 'url' => '/'],
        ['title' => 'Задачи', 'options' => ['class' => 'active']]
    ]
);

//выбранные фильтры задач - формирование ссылки
$filtersArr = [];
$filters = ['status','type'];

foreach ($filters as $filter) {
    if($$filter != ''){
        $filtersArr[$filter] = $$filter;
    }
}

?>
<?= $this->Breadcrumbs->render() ?>
<h1>Задачи</h1>
<div class="row">
    <?= $this->element('sidebar', [
        'filtersArr' => $filtersArr,
        'owner' => $owner,
        'status' => $status,
        'statuses' => $statuses,
        'type' => $type,
        'types' => $types]);?>
    <div class="col-lg-10 tasks index large-9 medium-8 columns content">
        <table cellpadding="0" cellspacing="0" class="table table-dark table-striped table-responsive">
            <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name', 'Задача') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status', 'Статус') ?></th>
                <th scope="col"><?= $this->Paginator->sort('date_created','Создана') ?></th>
                <th scope="col"><?= $this->Paginator->sort('date_updated','Изменена') ?></th>
                <th scope="col"><?= $this->Paginator->sort('bug_type','Тип') ?></th>
                <th scope="col"><?= $this->Paginator->sort('author','Автор') ?></th>
                <th scope="col"><?= $this->Paginator->sort('worker','Исполнитель') ?></th>
                <th scope="col" class="actions"><?= __('Действия') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= $this->Number->format($task->id) ?></td>
                    <td><?= $this->Html->link($task->name, ['action' => 'view', $task->id]) ?></td>
                    <td><?= $task->status_name ?></td>
                    <td><?= h($task->date_created) ?></td>
                    <td><?= h($task->date_updated) ?></td>
                    <td><?= $task->type_name ?></td>
                    <td><?= $task->author->name ?></td>
                    <td><?= isset($task->worker->name) ? $task->worker->name : 'не назначено' ?></td>
                    <td class="actions">
                        <?= $task->mayEdit($uid) ? $this->Html->link(__('Edit'), ['action' => 'edit', $task->id]) : '' ?>
                        <?= $task->mayDelete($uid) ? $this->Form->postLink(__('Delete'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id)]) : '' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->numbers() ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => 'Страница {{page}} из {{pages}}, запись(ей) {{current}} из {{count}}']) ?></p>
        </div>
    </div>
</div>
