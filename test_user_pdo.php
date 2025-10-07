<?php
require 'user-pdo.php';

$u = new UserPDO();
var_dump($u->register('pdoLogin','pass123','pdo@mail.com','Jean','PDO'));
$u2 = new UserPDO();
var_dump($u2->connect('pdoLogin','pass123'));

?>