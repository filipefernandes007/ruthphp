<?php

ini_set('display_errors', '1');

/**
 * Classe pré-definida para User.
 * 
 * @author Filipe Fernandes
 */

class ModelUser extends Model {

    public function ModelUser() {
        parent::init();
        
        $this->table = "users";
        $this->vo    = "UserVO"; 
        $this->primaryKey = "id";
    }
    
    public function getAll() {
        $query = "SELECT * FROM {$this->table};";
        
        return parent::query($query);
    }
    
    public function getAllRole($x) {
        $vo = new UserVO();
        $vo->setRole($x);
        
        $sqlParam        = new SqlParam();
        $sqlParam->setVO($vo);
        $sqlParam->table = $this->table;
        $sqlParam->where = "role = ?";
        $sqlParam->orderby = "role DESC";
        $sqlParam->limit = $x;
        
        return parent::Select($sqlParam);
    }
    
     /**
     * [Session]
     * [Context (modules/cms)]
     * [Roles (0,1)]
     */
    
    public function getById($i) {
        return parent::getById($i);
    }
    
    public function delete(SqlParam $obj) {
        return parent::DeleteById($obj);
    }
    
    public function update(SqlParam $obj) {
        $sqlParam        = new SqlParam();
        $sqlParam->setVO($obj->vo);
        $sqlParam->table = $this->table;
        $sqlParam->primaryKey = $this->primaryKey;
        
        return parent::Update($sqlParam);
    }
    
    /**
     * [Session]
     * [Context (modules/cms)]
     * [Roles (0,1)]
     */
    
    public function insert(SqlParam $obj) {
        $sqlParam        = new SqlParam();
        $sqlParam->setVO($obj->vo);
        $sqlParam->table = $this->table;
        
        return parent::Insert($sqlParam);
    }
    
    public function printAll() {
        print("All");
    }

}
