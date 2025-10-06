<?php
class UserPDO {

    private $id;
    public $login;
    public $password; // hash
    public $email;
    public $firstname;
    public $lastname;

      public function __construct($login = '', $password = '', $email = '', $firstname = '', $lastname = '') {
        $this->id = null;
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    private function pdo(): PDO {
        return new PDO(
            'mysql:host=localhost;dbname=classes;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

}