<?php
class UserPDO {

    private $id;
    public $login;
    public $password; // hash
    public $email;
    public $firstname;
    public $lastname;
    
    private function pdo(): PDO {
        return new PDO(
            'mysql:host=localhost;dbname=classes;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
      public function __construct($login = '', $password = '', $email = '', $firstname = '', $lastname = '') {
        $this->id = null;
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }


    public function register($login, $password, $email, $firstname, $lastname): array {
        $pdo = $this->pdo();
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$login, $hash, $email, $firstname, $lastname]);

        $this->id = (int)$pdo->lastInsertId();
        $this->login = $login;
        $this->password = $hash;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        return [
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];
    }

}