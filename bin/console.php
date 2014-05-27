<?php

ini_set('error_reporting', E_ALL);

if(!defined("ROOT")) {
    define("ROOT", dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
}

$def = file_get_contents(ROOT . "config" . DIRECTORY_SEPARATOR . "definitions.txt");
eval($def);

$args = array();

include_once CORE     . '/Connection/Connection.php';
include_once CORE_LIB . '/Config.php';
include_once ROOT     . '/application/modules/builder/lib/ProcessFiles.php';
include_once ROOT     . '/application/modules/builder/lib/ProcessDatabase.php';
include_once ROOT     . '/application/modules/builder/lib/ProcessCRUD.php';
include_once ROOT     . '/application/modules/builder/lib/ProcessTypes.php';

$tmpFolder    = "tmp-files";
$voFolder     = $tmpFolder . DIRECTORY_SEPARATOR . "vo";
$modelsFolder = $tmpFolder . DIRECTORY_SEPARATOR . "models";

$conn = Connection::getInstance();
$conn->init(Config::init()->Host(), Config::init()->User(), Config::init()->Password(), Config::init()->Database());

if(null === $conn->getConn())
    exit("Erro na ligação à base de dados. Confira em config.ini as suas credenciais.");

$comando = isset($argv[1]) ? $argv[1] : null;
$valor1  = isset($argv[2]) ? $argv[2] : null;
$valor2  = isset($argv[3]) ? $argv[3] : null;

if(!isset($comando)) {
    shell_exec("bin/console --help");
    return;
}

if(empty($comando))
    exit("Introduza um comando válido. Comando nulo!\n");

if(!is_dir($tmpFolder)) {
    mkdir ($tmpFolder);
    chmod($tmpFolder, 0777);
}

if(!is_dir($modelsFolder)) {
    mkdir($modelsFolder);
    chmod($modelsFolder, 0777);
}

if(!is_dir($voFolder)) {
    mkdir($voFolder);
    chmod($voFolder, 0777);
}

switch ($comando) {
    case '--help':
        print("\n--write-vo [database-table]: cria um ficheiro VO com base numa tabela da base de dados guarda-o em tmp-files\n");
        print("\n--write-models: cria todos os ficheiros VO e respectivos models, e guarda-os em tmp-files\n");
        print("\n--write-models [database-table]: cria um ficheiro VO com base numa tabela da base de dados, e o respectivo model guarda-o em tmp-files\n");
        die;
        
        break;
    case '--write-vo':
        if(!isset($valor1))
            exit("Não indicou qual a tabela da base de dados que pretende ver reflectida como VO.");
        
        if(!isset($valor2)) {
            ProcessDatabase::init(Config::init()->Database(), $conn, $valor1);
            ProcessCRUD::setTables(ProcessDatabase::getTables());

            $res  = ProcessCRUD::makeVO(false);
            $file = strtolower($valor1);
            file_put_contents(ROOT . "tmp-files/{$file}VO.php", $res);
            shell_exec("open tmp-files/{$file}VO.php");
        }
        
    break;
    
    case '--write-models':
        ProcessCRUD::setProjectFolder($tmpFolder . DIRECTORY_SEPARATOR);
        ProcessCRUD::setVoFolder($voFolder . DIRECTORY_SEPARATOR);
        ProcessCRUD::setClassFolder($modelsFolder . DIRECTORY_SEPARATOR);
        
        if (isset($valor1) && !empty($valor1)) {
            ProcessDatabase::init(Config::init()->Database(), $conn, $valor1);
        } else {        
            ProcessDatabase::init(Config::init()->Database(), $conn);
        }
        
        ProcessCRUD::setTables(ProcessDatabase::getTables());
        ProcessCRUD::init();
    break;
}

print("End.");