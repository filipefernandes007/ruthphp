<?php

/**
 *
 * Invoca um método de uma classe php com os respectivos argumentos.
 *
 * @author Filipe Fernandes
 *
 * Created: 21/04/2013
 *
 * Changed: 18/05/2013
 *
 */

class RPC {

    public function RPC() {

    }

    public function call($class, $method, $args, $type) {
        if(Config::init()->Stage() == DEVELOPMENT)
            ini_set('display_errors', '1');

        $result = null;

        if(!is_null($class) && class_exists($class)) {
            if (!is_null($method)) {
                if(method_exists($class, "getInstance")) {
                    $obj = $class::getInstance();
                } else {
                    $obj = new $class();
                }

                //$arguments = array(); // sem argumentos

                if (is_callable(array($class, $method))) {   // se é método da classe
                    $arguments = null;

                    if (!is_null($args)) {  // se é passado um value object ou um array como argumento
                        $argTemp = $args;
                        $args    = json_decode(stripslashes($args));

                        if(!$args) // trata-se de uma string simples
                            $args = $argTemp;

                        if(is_object($args)) {

                            //$classInstance  = new $class();
                            //$classInstance  = $obj;
                            $vo_name        = $obj->getVO();
                            $voInstance     = new $vo_name();

                            $arguments      = $voInstance->getProp($args);
                        } else {
                            // um só argumento
                            //$arguments[] = $args; // ex: &args=31

                            // verificar se há vírgulas a separar os argumentos
                            if(strpos($args, ",") > 0) {
                                $arguments = explode(",", $args);
                            } else {
                                $arguments = $args;
                            }

                        }
                    }

                    if(!is_null($arguments) && is_array($arguments))
                        $result = call_user_func_array(array($obj, $method), $arguments);
                    else
                        $result = call_user_func(array($obj, $method), $arguments);

                } else {
                    // método não existe
                    //exit("Acesso negado! Exit code: 3");
                    throw new BadMethodCallException("Acesso negado! Exit code: 3", 0);
                }
            } else {
                // método não definido
                //exit("Acesso negado! Exit code: 2");
                throw new BadMethodCallException("Acesso negado! Exit code: 2", 0);
            }
        } else {
            // classe nula ou ficheiro inexistente
            //exit("Acesso negado! Exit code: 1");
            throw new Exception("Acesso negado! Exit code: 1", 0);
        }

        // conforme o tipo de dados que se prentend em output, conforme o seu tratamento
        switch($type) {
            case "xml":
                return $this->returnXML($result);
            break;

            case "json":
                return $this->returnJSON($result);
            break;

            default:
                return $result;
            break;
        }
    }

    /**
     *
     * Retorna os valores em JSON
     *
     * @param  array $mixed
     * @return JSON
     */

    private function returnJSON($result) {
        $list   = array();

        if($result && sizeof($result) > 0) {
            if(!is_array($result))
                throw new ReflectionException("É esperado um array como resultado para JSON, quando o resultado é do tipo " . gettype($result));

            $class  = get_class($result[0]);
            $vo     = new $class();

            for($i = 0; $i < sizeof($result); $i++) {
                if(is_object($result[$i]))
                    $list[] = $vo->getProp($result[$i]);
            }
        }

        return json_encode($list);
    }

    /**
     *
     * Retorna o resultado em XML
     *
     * @param array $result
     * @return XML
     */

    private function returnXML($result) {
        if(!is_array($result))
            throw new ReflectionException("É esperado um array como resultado para XML, quando o resultado é do tipo " . gettype($result));

        header ("Content-type: text/xml");

        $xml    = new SimpleXMLElement("<?xml version=\"1.0\"?><root></root>");
        $array  = json_decode($this->returnJSON($result), true); // IMPORTANTE! Transformar num array de arrays

        foreach($array as $arrayValue) {
            $object     = $xml->addChild("object"); // num novo nó
            $arrayValue = @array_flip($arrayValue);  // é necessário inverter o array, caso contrário os resultados saem trocados

            @array_walk_recursive($arrayValue, array($object, 'addChild'));
        }

        return $xml->asXML();
    }

}
