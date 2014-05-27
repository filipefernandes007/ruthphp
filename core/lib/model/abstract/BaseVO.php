<?php

/**
 * 
 * Base para herança de todos os ValueObjects
 *
 * @author Filipe Fernandes
 */

abstract class BaseVO {
    
    public function BaseVO($obj = null) {
        if($obj) {
            if(is_object($obj)) { // é um objecto
                $std = $this->getProp($obj);
                
                foreach($std as $key => $prop) {
                    $this->$key = $prop;
                }
                
            } elseif(is_array($obj)) {
                foreach($obj as $key => $value)
                    $this->$key = $value;    
            }
        }
    }
    
    /**
     * 
     * Este método permite extrair as propriedades de um objecto, mesmo quando são privados
     * 
     * Caso se considerem ValueObjects com propriedades de visibilidade pública, então não
     * há necessidade de ter este método.
     * 
     * @param object $obj
     * @return object
     */
    
    public function getProp($obj) {
        return Debug::getProp($obj);
    }
}
