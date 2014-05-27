<?php

    /**
     *
     * Credenciais para acesso à base de dados. Leitura e parse do ficheiro ini.
     * 
     * @author Filipe Fernandes
     */

    class Config {
        private static $host;
        private static $user;
        private static $pw;
        private static $db;
        private static $adapter;
        private static $ini;
        private static $instance;
        private static $layout;

        private static $stage;
        private static $path;
        private static $language;
        
        private static $allwaysAllowRaw = false;

        /**
         * 
         * Instancia a classe Credentials e efectua o parse do ficheiro init
         * 
         * @return Config
         */

        public static function init() {
            $class = __CLASS__;     // $class fica com o nome da classe com o proposito da instanciar a seguir

            if (!isset(self::$instance)) { // se ainda não tiver sido instanciado, criar instancia
                self::$instance = new $class();	// instância de API

                if(is_null(self::$ini)) {
                    self::$ini = parse_ini_file(CONFIG . DIRECTORY_SEPARATOR . "config.ini", true);

                    if(!defined("STAGE"))
                        define("STAGE", self::$ini["stage"]);

                    if(!defined("PATH"))
                        define("PATH", self::$ini[STAGE]["path"]);

                    if(!defined("LANGUAGE"))
                        define("LANGUAGE", self::$ini["language"]);

                    self::$stage    = self::$ini["stage"];
                    self::$path     = self::$ini[STAGE]["path"];
                    self::$language = self::$ini["language"];

                    self::setUp();
                }
            }
            
            self::defineStage();
            
            return self::$instance;
        }
        
        /**
         * Da extracção de config.ini guardar os dados
         */
        private static function setUp() {
            self::$adapter         = self::$ini[STAGE]["adapter"];
            self::$host            = self::$ini[STAGE]["host"];
            self::$user            = self::$ini[STAGE]["username"];
            self::$pw              = self::$ini[STAGE]["password"];
            self::$db              = self::$ini[STAGE]["dbname"];
            self::$allwaysAllowRaw = self::$ini[STAGE]["allwaysAllowRaw"];

            if(isset(self::$ini[STAGE]["layout"]))
                self::$layout  = self::$ini[STAGE]["layout"];
            else
                self::$layout = NULL;

        }

        public function Host() {
            return self::$host;
        }

        public function User() {
            return self::$user;
        }

        public function Password() {
            return self::$pw;
        }

        public function Database() {
            return self::$db;
        }

        public function Adapter() {
            return self::$adapter;
        }

        public function Layout() {
            return self::$layout;
        }
        
        public function Raw() {
            return self::$allwaysAllowRaw;
        }

        public function Stage() {
            return STAGE;
        }

        public function Path() {
            return PATH;
        }

        public function Language() {
            return LANGUAGE;
        }
        
        /**
        *
        * Define o estágio de desenvolvimento: development, staging ou production
        *
        * @param string $stage
        */

        private static function defineStage() {
           switch(strtoupper(self::$stage)) {
               case PRODUCTION:
                   ini_set('error_reporting', 0);
               break;

               case STAGING:
                   self::iniSet();
               break;

               default :
                   self::iniSet();
               break;
           }
        }

        /**
        * 
        * Configurar php settings
        */
        private static function iniSet() {
           $config = include CONFIG . DIRECTORY_SEPARATOR . "php-settings.php";

           if(is_array($config) && array_key_exists('php-settings', $config)) {
               $phpSettings = $config['php-settings'];

               if(is_array($phpSettings)) {
                   foreach($phpSettings as $key => $value) {
                       ini_set($key, $value);
                   }
               }
           }
        }
    }
