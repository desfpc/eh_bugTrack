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
        ['title' => h($user->name), 'options' => ['class' => 'active']]
    ]
);

?>
<?= $this->Breadcrumbs->render() ?>
<h1><?= h($user->name) ?></h1>
<div class="row">
    <div class="col-sm-12">
        <table class="vertical-table table table-dark">
            <tr>
                <th scope="row"><?= __('Name') ?></th>
                <td><?= h($user->name) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Login') ?></th>
                <td><?= h($user->login) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Id') ?></th>
                <td><?= $this->Number->format($user->id) ?></td>
            </tr>
        </table>
    </div>
</div>
