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
        
        $insert->bind_param('sssss', $login, $password, $email, $firstname,$lastname); 

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
}




