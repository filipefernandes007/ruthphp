<?php


/**
 * Description of UserVO
 *
 * @author Filipe Fernandes
 */

class UserVO extends BaseVO {
    protected $id;
    protected $username;
    protected $password;
    protected $last_login;
    protected $role;
    
    public function UserVO($obj = null) {
        
        if($obj)
            parent::BaseVO($obj);
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getLastLogin() {
        return $this->lastLogin;
    }
    
    public function getRole() {
        return $this->role;
    }
    
    public function setId($id) {
        $this->id = $id;
        
        return $id;
    }
    
    public function setUsername($username) {
        $this->username = $username;
        
        return $this->username;
    }
    
    public function setPassword($pw) {
        $this->password = $pw;
        
        return $this->password;
    }
    
    public function setLastLogin($login) {
        $this->last_login = $login;
        
        return $this->last_login;
    }
    
    public function setRole($level) {
        $this->role = (int)$level;
        
        return $this->role;
    }
}

