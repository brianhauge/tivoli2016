<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 20:24
 */

setlocale(LC_ALL, "da_DK");

spl_autoload_register(function ($class) {
    include $class . '.php';
    include 'model/' . $class . '.php';
});

$smsBody = new SmsContent();
$smsBody->setSmscontent($_GET['body']);

file_put_contents("tmp.txt",$smsBody->getSmscontent());