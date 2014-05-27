<?php

    if(!defined("ROOT")) {
        define("ROOT", dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
    }

    $result = file_get_contents(ROOT . "config" . DIRECTORY_SEPARATOR . "definitions.txt");

    eval($result);

    $controller = new Controller();
    $controller->run();
