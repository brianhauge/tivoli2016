<?php
/**
 * Created by PhpStorm.
 * User: bhansen
 * Date: 08/08/16
 * Time: 20:24
 */

setlocale(LC_ALL, "da_DK");

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$smsBody = new SmsModel();
$smsBody->setSmscontent($_GET['body']);

print("SMS Content: ".$smsBody->getSmscontent() . " Point: " . $smsBody->getPoint() . " Post: " . $smsBody->getPost() . " Hold: " . $smsBody->getTeam());

$db = new DbModel();
$db->insertScore($smsBody->getTeam(), $smsBody->getPoint(), $smsBody->getPost(), $_GET['sender']);