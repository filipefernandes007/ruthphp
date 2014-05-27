<?php

/**
 * Classe para controlo de excepções. Todas devem desaguar aqui.
 *
 * @author Filipe
 */

class MasterException {
    
    public static function init() {
        set_exception_handler(array(__CLASS__, "ExceptionHandler"));
    }

    public static function ExceptionHandler(Exception $e) {
        if(Config::init()->Stage() != DEVELOPMENT)
            $msg = "Error!";
        else
            $msg = "<p>Message: {$e->getMessage()}</p> <br/><p>Trace: {$e->getTraceAsString()}</p><br/><p>File: {$e->getFile()}</p><br/><p>Line: {$e->getLine()}</p>";

        $view = new ViewException($msg);
        
        exit();
    }

}
