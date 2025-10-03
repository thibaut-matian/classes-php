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
