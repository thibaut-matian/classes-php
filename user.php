<?php

class User {
    
    private $id; 
    public $login;     
    public $password; 
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

    public function register($login, $password, $email, $firstname, $lastname){
        $mysqli = new mysqli ('localhost', 'root', '','classes'); 
        if ($mysqli->connect_errno){
            die('ERREUR CONNEXION MySQL: ' . $mysqli->connect_error); 
        }
        // hashage du password
        $hash = password_hash($password, PASSWORD_BCRYPT); 

        $insert = $mysqli->prepare("INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)");

        if (!$insert){
            $mysqli->close();
            return['error' => 'Preparation requête échouée'];
        }
        
        $insert->bind_param('sssss', $login, $hash, $email, $firstname,$lastname); 

        if (!$insert->execute()){
            $err= $insert->error;
            $insert->close();
            $mysqli->close();
            return['error' => 'Insertion échouée: ' . $err]; 
        }
        
        $this->id = $insert->insert_id;
        $this->login = $login;
        $this->password = $hash; // on garde le hash 
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $insert->close();
        $mysqli->close(); 

        return [
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];
}


public function connect($login, $password){
    $mysqli = new mysqli('localhost', 'root', '', 'classes'); 
    if ($mysqli->connect_errno){
        die('ERREUR CONNEXION MySQL: ' . $mysqli->connect_error); 
    } 

    // NE PAS re-hasher ici
    $conn = $mysqli->prepare("SELECT id, login, password, email, firstname, lastname FROM utilisateurs WHERE login = ?");
    if (!$conn){
        $mysqli->close();
        return ['error' => 'Préparation requête échouée'];
    }

    $conn->bind_param('s', $login);
    $conn->execute();
    $conn->store_result();

    if ($conn->num_rows === 0){
        $conn->close();
        $mysqli->close();
        return ['error' => 'Login inconnu'];
    }

    $conn->bind_result($id, $dbLogin, $dbHash, $dbEmail, $dbFirstname, $dbLastname);
    $conn->fetch();

    // Comparer le mot de passe tapé au hash stocké
    if (!password_verify($password, $dbHash)){
        $conn->close();
        $mysqli->close();
        return ['error' => 'Mot de passe incorrect'];
    }

    $this->id = $id;
    $this->login = $dbLogin;
    $this->password = $dbHash;
    $this->email = $dbEmail;
    $this->firstname = $dbFirstname;
    $this->lastname = $dbLastname;

    $conn->close();
    $mysqli->close();

    return [
        'id' => $this->id,
        'login' => $this->login,
        'email' => $this->email,
        'firstname' => $this->firstname,
        'lastname' => $this->lastname
    ];
}

public function disconnect() {
    
    $this->id = null;
    $this->login = '';
    $this->password = '';
    $this->email = '';
    $this->firstname = '';
    $this->lastname = '';
    return true;
}
}


