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
            ['controller' => 'tasks', 'action' => 'index', '?' => $filtersArr],
            ['class' => 'list-group-item list-group-item-action list-group-item-dark' . ($owner == '' ? ' active' : '')]) ?>
        <?= $this->Html->link('Созданные',
            ['controller' => 'tasks', 'action' => 'index', '?' => array_merge(['owner' => 'author'], $filtersArr)],
            ['class' => 'list-group-item list-group-item-action list-group-item-dark' . ($owner == 'author' ? ' active' : '')]) ?>
        <?= $this->Html->link('Назначенные',
            ['controller' => 'tasks', 'action' => 'index', '?' => array_merge(['owner' => 'worker'], $filtersArr)],
            ['class' => 'list-group-item list-group-item-action list-group-item-dark' . ($owner == 'worker' ? ' active' : '')]) ?>
    </div>
    <label class="label-menu" for="status-control">Статус:</label>
    <select class="form-control task-control" id="status">
        <option value="" <?= $status == '' ? 'selected' : '' ?>>
            Все
        </option>
        <?php foreach ($statuses as $key => $value): ?>
            <option value="<?= $key ?>" <?= $status == $key ? 'selected' : '' ?>>
                <?= __($value) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <label class="label-menu" for="status-control">Тип бага:</label>
    <select class="form-control task-control" id="type">
        <option value="" <?= $type == '' ? 'selected' : '' ?>>
            Все
        </option>
        <?php foreach ($types as $key => $value): ?>
            <option value="<?= $key ?>" <?= $type == $key ? 'selected' : '' ?>>
                <?= __($value) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
