<?php
declare(strict_types = 1);
/**
 * @var array $filtersArr - массив с фильтрами
 * @var string $owner - значение фильтра по принадлежности пользователя к задачи
 * @var string $status - значение фильтра по статусу задачи
 * @var string $type - значение фильтра по типу задачи
 * @var array $statuses - возможные статусы задачи
 * @var array $types - возможные типы задачи
 */



?><div class="col-lg-2 site-sidebar">
    <?= $this->Html->link(
        'Новая задача',
        $this->Url->build([
            "controller" => "Tasks",
            "action" => "add"]),
        ['class' => 'btn btn-success btn-block']) ?>
    <div class="list-group list-group-tasks">
        <?= $this->Html->link('Все',
            ['controller' => 'tasks', 'action' => 'index', '?' => array_merge($filtersArr, ['owner' => 'all'])],
            ['class' => 'list-group-item list-group-item-action list-group-item-dark' . ($owner == '' ? ' active' : '')]) ?>
        <?= $this->Html->link('Созданные',
            ['controller' => 'tasks', 'action' => 'index', '?' => array_merge($filtersArr, ['owner' => 'author'])],
            ['class' => 'list-group-item list-group-item-action list-group-item-dark' . ($owner == 'author' ? ' active' : '')]) ?>
        <?= $this->Html->link('Назначенные',
            ['controller' => 'tasks', 'action' => 'index', '?' => array_merge($filtersArr, ['owner' => 'worker'])],
            ['class' => 'list-group-item list-group-item-action list-group-item-dark' . ($owner == 'worker' ? ' active' : '')]) ?>
    </div>
    <?= $this->Form->control('status', [
        'options' => array_merge(['' => 'Все'],$statuses),
        'class' => 'form-control task-control',
        'default' => $status
    ]); ?>
    <?= $this->Form->control('type', [
        'options' => array_merge(['' => 'Все'],$types),
        'class' => 'form-control task-control',
        'default' => $type
    ]); ?>
</div>
