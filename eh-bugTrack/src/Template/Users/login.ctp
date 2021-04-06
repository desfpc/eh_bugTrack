<?php
declare(strict_types = 1);

$this->set('containerClass','h-100 container-login');

?>
<?= $this->Form->create(null, ['class' => 'form-signin']) ?>
    <h1>Авторизация</h1>
<?= $this->Form->control('login', ['label' => false, 'placeholder' => 'Логин']) ?>
<?= $this->Form->control('pass', ['label' => false, 'placeholder' => 'Пароль', 'type' => 'password']) ?>
<?= $this->Form->button('Войти', ['class' => 'btn-block btn-primary']) ?>
<?= $this->Form->end() ?>
