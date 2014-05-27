<?php

/**
 * Class para autoload das classes de todo o projecto
 *
 * @author Filipe
 *
 * @created 18/05/2013
 * @update  18/05/2013
 * @version 1.0.0
 */
class AutoLoad {

    private $arrayExcludeFolderClassElegible = array(0 => "public"); // as pastas fora do autoLoader

    /**
     *
     * Carrega todas as classes e models a partir de uma pasta ($corePath).
     *
     * @param string $corePath
     */

    public function autoLoader($path) {
        if (!file_exists($path))
            return;

        $files   = scandir($path);
        $classes = array();

        $code = "";

        foreach ($files as $file) {
            $_file = $path . "/" . $file;

            if ($file != '.' && $file != '..' && $file != "modules") { // evitar a própria pasta e a parent
                if (is_dir($_file)) {
                    $this->autoLoader($_file);
                } else {
                    $file_parts = pathinfo($file);

                    if ($file_parts['extension'] == "php" && $this->isClassFolderElegible($_file)) {
                        $className = preg_replace("/\\.[^.\\s]{3,4}$/", "", $file);

                        if (!class_exists($className)) {

                            spl_autoload_register(function ($className) use($_file, $code) {
                                if(include_once($_file)) {
                                    $code .= file_get_contents($_file, FILE_USE_INCLUDE_PATH) . '?>';
                                }
                            });
                        }
                    }
                }
            }
        }

        //$this->render($code);
    }

    /**
     *
     * @param string $path
     * @return null|void
     */
    public function loadModule($path) {
        if (!file_exists($path))
            return;

        $files = scandir($path);

        foreach ($files as $file) {
            $_file = $path . "/" . $file;

            if ($file != '.' && $file != '..') { // evitar a própria pasta e a parent
                //if ($file != '.' && $file != '..') { // evitar a própria pasta e a parent
                if (is_dir($_file)) {
                    $this->autoLoader($_file);
                } else {
                    $file_parts = pathinfo($file);

                    //if($file_parts['extension'] == "php" && $this->isFileClass($_file) && $this->isClassFolderElegible($_file)) {
                    if ($file_parts['extension'] == "php" && $this->isClassFolderElegible($_file)) {
                        $className = preg_replace("/\\.[^.\\s]{3,4}$/", "", $file);

                        if (!class_exists($className)) {
                            include_once($_file);
                        }
                    }
                }
            }
        }
    }

    /**
     *
     * @TODO
     *
     * AINDA NÃO FOI TESTADO
     *
     * Procura uma classe em todo o projecto, e efectua o respectivo require
     *
     * @param string $class
     * @return boolean
     */
    public function findAndLoadClass($class) {
        if (!file_exists(ROOT))
            return false;

        $files = scandir(ROOT);

        foreach ($files as $file) {
            $_file = $path . "/" . $file;

            if ($file != '.' && $file != '..') { // evitar a própria pasta e a parent
                if (is_dir($_file)) {
                    $this->autoLoader($_file);
                } else {
                    $file_parts = pathinfo($file);
                    //if($file != ".DS_Store") {
                    if ($file_parts['extension'] == "php" && $this->isFileClass($_file) && $this->isClassFolderElegible($_file)) {
                        $className = preg_replace("/\\.[^.\\s]{3,4}$/", "", $file);

                        if (!class_exists($className) && $className == $class) {
                            require_once($_file);

                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     *
     * Define as pastas que não podem ser lidas durante o autoLoader,
     * geralmente porque contêm ficheiros que não são classes
     *
     * @param string $dir
     * @return boolean
     */
    public function isClassFolderElegible($dir) {
        $folder = dirname($dir);
        $array = explode("/", $folder);
        $parent = $array[sizeof($array) - 1];

        if (!in_array($parent, $this->arrayExcludeFolderClassElegible))
            return true;

        return false;
    }

    /**
     *
     * Verifica se o ficheiro define uma classe
     *
     * @param strng $file
     */
    protected function isFileClass($file) {
        $fp = fopen($file, 'r');
        $buffer = fread($fp, 512);
        fclose($fp);

        if (preg_match('/class\s+(\w+)(.*)?\{/', $buffer, $matches))
            return true;

        return false;
    }

    /**
     *
     * @param type $code
     */
    private function render($code) {
        //$_COOKIE['code'] = null;

        if(!isset($_COOKIE['code'])) {
            ob_start();
            $cd = eval('?>' . $code);
            $output = ob_get_contents();

            $_COOKIE['code'] = $code;

            ob_end_clean();
        } else {
            ob_start();
            $cd = eval('?>' . $_COOKIE['code']);
            $output = ob_get_contents();
            //$_COOKIE['code'] = $code;
            ob_end_clean();
        }

        print($output);
    }

}
