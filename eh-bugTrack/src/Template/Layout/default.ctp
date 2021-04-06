<?php
declare(strict_types = 1);
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Bug Tracker Lite';

//если пользователь авторизированный, получаем его данные для главного меню
$authUserID = $this->request->getSession()->read('Auth.User.id');
if($authUserID){
    $authUserLogin = $this->request->getSession()->read('Auth.User.login');
}

//если нет $containerClass, то его объявляем
if(!isset($containerClass)){
    $containerClass='';
}

?>
<?= $this->Html->docType() ?>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->script([
    'https://code.jquery.com/jquery-3.5.1.slim.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js'
    ]) ?>
    <?= $this->Html->script('site.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="text-light">
    <div class="wrapper">
        <?= $this->Navbar->create(['name' => 'BugTracker', 'url' => '/', 'image' => '/img/beetle.svg'],
                ['fixed' => 'top',
                'theme' => 'dark',
                'src' => '/img/beetle.svg']) ?>
        <?php if($authUserID): ?>
            <?= $this->Navbar->beginMenu() ?>
            <?= $this->Navbar->link('Задачи', '/tasks') ?>
            <?= $this->Navbar->link('Пользователи', '/users') ?>
            <?= $this->Navbar->endMenu() ?>
            <?= $this->Html->link(
                $authUserLogin,
                $this->Url->build([
                    "controller" => "Users",
                    "action" => "edit",
                    $authUserID,]),
                ['class' => 'btn btn-light']) ?>
            <?= $this->Html->link(
                'Выход',
                $this->Url->build([
                    "controller" => "Users",
                    "action" => "logout",]),
                ['class' => 'btn btn-link']) ?>

        <?php endif; ?>
        <?= $this->Navbar->end() ?>
        <div class="container-fluid container-main <?=$containerClass?>">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
        <footer class="footer">
            <div class="container">
                <span class="text-light">© <?= date('Y') ?> Eggheads Solutions</span>
            </div>
        </footer>
    </div>
    <?= $this->fetch('script') ?>
</body>
</html>
