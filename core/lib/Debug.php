<?php

/**
 *
 * Debug directo para o ecrã ou para a consola javascript.
 *
 * @author Filipe Fernandes
 */

class Debug {

    /**
     *
     * Chamada da consola javascript do contexto php
     *
     * @param type $data
     */

    public static function consoleLog($data) {
        if(is_object($data) || is_array($data))
            $var = json_encode($data);
        else
            $var = $data;

        print("<script>console.log({$var});</script>");
    }

    /**
     *
     * Mostra os dados formatados, seja um array ou objecto, um array de arrays ou arrays de objectos.
     *
     * @param type $data
     */

    public static function dump($data, $exit = true) {
        print("<pre>");

        if(is_object($data) || is_array($data))
            print_r($data);
        else
            print($data);

        if($exit)
            die;
    }

    /**
     *
     * Obtém as propriedades de qualquer objecto, mesmo sendo privado.
     *
     * @param stdClass $obj
     * @return \stdClass
     */

    public static function getProp($obj) {
        if($obj && is_object($obj)) { // é um objecto?
            // preg_replace retirado de http://stackoverflow.com/users/1049247/seorch-me
            $properties = json_decode(preg_replace('/\\\\u([0-9a-f]{4})|'.get_class($obj).'/i', '', json_encode((array) $obj)));

            $obj = new stdClass();

            foreach($properties as $key => $value) {
                $new_key       = str_replace('*', '', $key); // o que estiver definido como privado ou protected aparece com *, que deve ser removido
                $obj->$new_key = $properties->$key;
            }

            unset($properties);

            // retornar como array
            //return get_object_vars($obj);
            return $obj;
        }

        return;
    }

    /**
     *
     * @param bool $exit
     */
    static public function whoCalledMe($exit = true) {
        $backtrace = debug_backtrace();

        var_dump('class: ' . $backtrace[1]['class']);
        var_dump('function: ' .$backtrace[1]['function']);

        if($exit)
            die;
    }

    /**
     *
     * @param bool $exit
     */
    static public function debugBacktrace($exit = true) {
        var_dump(debug_backtrace());

        if($exit)
            die;
    }
}
