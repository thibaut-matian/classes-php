<?php

require 'User.php';

$user = new User(
    'jdoe',
    'secret123',
    'jdoe@mail.com',
    'John',
    'Doe'
);

var_dump($user);

echo '<br>Login : ' . $user->login;
echo '<br>Password : ' . $user->password;
echo '<br>Email : ' . $user->email;
echo '<br>Firstname : ' . $user->firstname;
echo '<br>Lastname : ' . $user->lastname;

echo '<hr>';

$user = new User();
$resultat = $user->register('LoginTest', 'secret123', 'test@mail.com', 'Jean', 'Doe'); 
var_dump($resultat); 


echo '<hr>'; 

$user = new User();
$user->register('testLogin','abc123','t@mail.com','Jean','Test');
$u2 = new User();
var_dump($u2->connect('testLogin','abc123'));

echo '<hr>';


$user = new User();
$user->register('toDelete','pass123','d@mail.com','Jean','Delete');
var_dump($user->delete()); // true
var_dump($user);  


echo '<hr>';

$user = new User();
var_dump($user->isConnected());            
$user->register('log','pass','m@mail.com','A','B');
var_dump($user->isConnected());            
$user->disconnect();
var_dump($user->isConnected());   