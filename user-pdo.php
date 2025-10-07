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
   public function connect($login, $password): array {
        $pdo = $this->pdo();

        $stmt = $pdo->prepare("SELECT id, login, password, email, firstname, lastname FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        $row = $stmt->fetch();

        if (!$row) {
            return ['error' => 'Login inconnu'];
        }
        if (!password_verify($password, $row['password'])) {
            return ['error' => 'Mot de passe incorrect'];
        }

        $this->id = (int)$row['id'];
        $this->login = $row['login'];
        $this->password = $row['password'];
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
       public function disconnect(): bool {
        $this->id = null;
        $this->login = '';
        $this->password = '';
        $this->email = '';
        $this->firstname = '';
        $this->lastname = '';
        return true;
    }

     public function delete() {
        if ($this->id === null) {
            return ['error' => 'Aucun utilisateur connecté'];
        }
        $pdo = $this->pdo();
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$this->id]);

        $this->disconnect();
        return true;
    }

    public function update($login, $password, $email, $firstname, $lastname) {
        if ($this->id === null) {
            return ['error' => 'Aucun utilisateur connecté'];
        }
        $pdo = $this->pdo();

        $newHash = $this->password;
        if ($password !== null && $password !== '') {
            $newHash = password_hash($password, PASSWORD_BCRYPT);
        }

        $stmt = $pdo->prepare("UPDATE utilisateurs SET login = ?, password = ?, email = ?, firstname = ?, lastname = ? WHERE id = ?");
        $stmt->execute([$login, $newHash, $email, $firstname, $lastname, $this->id]);

        $this->login = $login;
        $this->password = $newHash;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        return [
            'id' => $this->id,
            'login' => $this->login,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];
    }
}