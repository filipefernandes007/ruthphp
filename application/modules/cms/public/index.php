<?php

    if(!defined("ROOT"))
        define("ROOT", realpath(dirname(__FILE__).'/../../../..') . DIRECTORY_SEPARATOR);

    $def = file_get_contents(ROOT . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "definitions.txt");
    eval($def);
    
    $controller = new Controller("cms");
    $controller->setViewDefault("ViewCmsLogin");
    
    if(Config::init()->Stage() == DEVELOPMENT)
        ini_set('display_errors', '1');
    
    $controller->run();
  