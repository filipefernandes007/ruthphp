<?php

/**
 * VO Genérico.
 *
 * @author Filipe Fernandes
 */
class GenericVO extends BaseVO {

    public function GenericVO($obj = null) {
        if ($obj)
            parent::BaseVO($obj);
    }
    
    /**
     * 
     * Vai permitir visualizar a propriedade mesmo que seja de visibilidade protegida ou privada
     * @param type $property
     * @return null
     */
    
    public function __get($property) {
        if (property_exists($this, $property))
            return $this->$property;
        
        return NULL;
    }

}
