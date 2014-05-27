<?php

/**
 * Classe para ler ficheiros sql. É preferível colocar os ficheiros sql na pasta sql, e invocá-los depois para serem executados
 * do que escrever directamente SQL nas páginas PHP.
 *
 * @author Filipe Fernandes
 */

class SqlFile {
    protected $lastFile;
    
    public function SqlFile() {
        if(!defined("SQL_PATH"))
            define("SQL_PATH", ROOT . "sql" . DIRECTORY_SEPARATOR);
    }
    
    /**
     * 
     * Retorna o conteúdo do ficheiro SQL.
     * 
     * @param string $filename
     * @return null
     */
    
    public function load($filename) {
        if(empty($filename))
            return NULL;
        
        $array = explode(".", $filename);
        
        $filename = $array[0] . ".sql";
        
        if(file_exists(SQL_PATH . $filename)) {
            $this->lastFile = file_get_contents(SQL_PATH . $filename);
            
            return $this->lastFile;
        }
        
        return NULL;
    }
    
    /**
     * 
     * @param string $proc
     * @todo
     */
    
    public function loadProcedure($proc) {
        
    }
    
    public function Where($array) {
        $i     = 0; 
        $num   = sizeof($array);
        $where = " WHERE ";
        
        foreach($array as $key => $value) {
            if(is_int($value))
                $where .= $key . " = " . (int) $value;
            elseif(is_string($value))
                $where .= $key . " = '{$value}'";
                
            if($i < $num - 1) {            
                $where .= " AND ";
            }
            
            $i++;
        }
        
        $this->lastFile .= $where;
        
        return $this->lastFile;
    }
    
    
    public function OrderBy($array, $type = "ASC") {
        $i     = 0; 
        $num   = sizeof($array);
        $order = " ORDER BY ";
        
        foreach($array as $key => $value) {
            
            $order .= $value;
                
            if($i < $num - 1) {            
                $order .= " AND ";
            }
            
            $i++;
        }
        
        $this->lastFile .= $order;
        
        return $this->lastFile;
    }
    
    public function GroupBy($array) {
        $i     = 0; 
        $num   = sizeof($array);
        $where = " GROUP BY ";
        
        foreach($array as $key => $value) {
            
            $where .= $value;
                
            if($i < $num - 1) {            
                $where .= " , ";
            }
            
            $i++;
        }
        
        $this->lastFile .= $where;
        
        return $this->lastFile;
    }
}
