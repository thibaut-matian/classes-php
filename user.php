<?php

class User {
    
    private $id; 
    public $login;      
    public $email;      
    public $firstname;  
    public $lastname;   

    public function __construct($login = '', $email = '', $firstname = '', $lastname = '') {
        $this->id = null;          
        $this->login = $login;     
        $this->email = $email;     
        $this->firstname = $firstname; 
        $this->lastname = $lastname;   
    }
}




