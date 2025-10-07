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

     public function register($login, $password, $email, $firstname, $lastname) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=classes;charset=utf8mb4', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die('ERREUR CONNEXION MySQL: ' . $e->getMessage());
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        try {
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
        } catch (PDOException $e) {
            return ['error' => 'Insertion échouée: ' . $e->getMessage()];
        }
    }

    public function connect($login, $password) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=classes;charset=utf8mb4', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            die('ERREUR CONNEXION MySQL: ' . $e->getMessage());
        }

        $stmt = $pdo->prepare("SELECT id, login, password, email, firstname, lastname FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return ['error' => 'Login inconnu'];
        }

        if (!password_verify($password, $row['password'])) {
            return ['error' => 'Mot de passe incorrect'];
        }

        $this->id = (int)$row['id'];
        $this->login = $row['login'];
        $this->password = $row['password']; // hash
        $this->email = $row['email'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];

        return [
            'id' => $this->id,
            'login' => $this->login,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];
    }

}