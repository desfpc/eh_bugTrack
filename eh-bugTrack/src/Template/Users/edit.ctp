<?php
declare(strict_types = 1);
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */

//хлебные крошки
$this->Breadcrumbs->add(
    [
        ['title' => 'Дашборд', 'url' => '/'],
        ['title' => 'Пользователи', 'url' => ['action' => 'index']],
        ['title' => $user->name, 'options' => ['class' => 'active']]
    ]
);

?>
<?= $this->Breadcrumbs->render() ?>
<h1><?= $user->name ?> - редактирование</h1>
<div class="row">
    <div class="col-sm-12">
        <?= $this->Form->create($user) ?>
        <fieldset>
            <?php
            echo $this->Form->control('name');
            echo $this->Form->control('login');
            echo $this->Form->control('pass');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
