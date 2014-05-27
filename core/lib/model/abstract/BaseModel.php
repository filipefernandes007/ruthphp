<?php

/**
 * Description of BaseModel
 * 
 * A classe base para Model, com os métodos primários para queries simples à base de dados
 *
 * @author Filipe
 */

class BaseModel {
    
    private   $conn;    // a ligação à base de dados
    
    protected function BaseModel() {
        $this->conn = Connection::getInstance();
        
        $this->conn->init();
    }
    
    /**
     * 
     * Devolve a instância de Connection
     * 
     * @return Connection
     */
    
    protected function getConn() {
        return $this->conn;
    }
    
    /**
     * 
     * Obter a última query feita à base de dados
     * 
     * @return string
     */
    
    protected function getLastQuery() {
        return $this->conn->getLastQuery();
    }
    
    /**
     * 
     * Método para queries com bind param
     * 
     * @param SqlParam $sqlParam
     * @return resource
     * @throws Exception
     */
    
    protected function bindQuery(SqlParam $sqlParam) {
        // Connection->PDO->prepare($sql)
        $res    = $this->getConn()->getConn()->prepare($sqlParam->sql);
        $param  = strpos($sqlParam->sql, ":");
        $array  = array();
        
        $res->closeCursor();
        
        $i = 1;
        
        if(!$param) {
            foreach($sqlParam->vo as $key => $value) { // para o caso da query ser do género ...WHERE calories < ? AND colour = ?
                if(!is_null($value)) {
                    $res->bindParam($i, $value, $this->getType($value));

                    $array[] = $value;
                    
                    $i++;
                }
            }
        } else { // para o caso da query ser do género ... WHERE calories < :calories AND colour = :colour
            foreach($sqlParam->vo as $key => $value) {
                if(isset($value) && $value != NULL) {
                    $res->bindParam(":{$key}", $value, $this->getType($value));
                    $array[$key] = $value;    
                }
            }
        }
                
        $result = $res->execute($array);
        $error  = $res->errorInfo();
        
        if(!$result)
            if(Config::init()->Stage() == DEVELOPMENT)
                throw new PDOException("Erro a executar a query! Query: {$sqlParam->sql}" . $error[2], 1);
            else
                throw new PDOException("Erro a executar a query!" . $error[2], 1);
        
        return $res;
    }


    /************************************************************************************/
    /* Utils                                                                            */
    /************************************************************************************/
    
    /**
     * 
     * Obtem o tipo do "objecto" e retorna o equivalente para o PDO
     * 
     * @param  type $value
     * @return type
     */
    
    protected function getType($value) {
        switch(gettype($value)) {
            case "integer":
                return PDO::PARAM_INT;
            break;
        
            default:
                return PDO::PARAM_STR;
            break;
        }
    }
}
