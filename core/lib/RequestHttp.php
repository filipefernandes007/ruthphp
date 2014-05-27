<?php

    /**
     * RequestHttp
     *
     * Classe para obter variáveis pré-definidas como $_GET, $_POST, $_REQUEST, $_SESSION, $_SERVER, $_COOKIE
     *
     * @author Filipe Fernandes
     *
     */

    class RequestHttp {
        const     STRIP_SCRIPT = "@<script[^>]*>.+</script[^>]*>@i";
        const     CSRF_MESSAGE = "Tentativa de ataque CSRF!";
        const     _UNSET       = true;
        
        public function __construct() {
            //ini_set('error_reporting', E_ALL);
        }
        
        /**
         *
         * @param  string $index
         * @return mixed|null
         */
        public static function Get($index = NULL) {
            if(!isset($index)) {
                return filter_input_array(INPUT_GET);
            } elseif(isset($index) && null !== filter_input(INPUT_GET, $index)) {
                return filter_input(INPUT_GET, $index);
            }

            return null;
        }

        /**
         *
         * @param  string $index
         * @return mixed
         */
        public static function Post($index = NULL) {
            if(!isset($index)) {
                return filter_input_array(INPUT_POST);
            } elseif(isset($index) && null !== filter_input(INPUT_POST, $index)) {
                return filter_input(INPUT_POST, $index);
            }

            return null;
        }

        /**
         *
         * @param  string $index
         * @return mixed
         */
        public static function Request($index = NULL) {
            if(!isset($index)) {
                return $_REQUEST;
            } elseif(isset($index) && isset($_REQUEST)) {
                return $_REQUEST[$index];
            }

            return null;
        }

        /**
         *
         * @param  string $index
         * @return mixed
         */
        public static function Server($index = null) {
            if(!isset($index)) {
                return filter_input_array(INPUT_SERVER);
            } elseif(isset($index) && null !== filter_input(INPUT_SERVER, $index)) {
                return filter_input(INPUT_SERVER, $index);
            }

            return null;
        }

        /**
         *
         * @param  string $index
         * @param  string $value
         * @param  bool   $unset
         * @return mixed
         */
        public static function Session($index = null, $value = null, $unset = false) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $session = $_SESSION;

            if($unset && !isset($value)) {
                unset($_SESSION[$index]);
            }

            if(isset($index)) {
                if(isset($value)) {
                    $_SESSION[$index] = $value;
                    $session = $_SESSION;
                }
                
                session_write_close();
                
                return isset($session[$index]) ? $session[$index] : null;
            }

            session_write_close();

            return $session;
        }

       /**
        * 
        * @param type $index
        * @param object|array $obj
        * @return type
        */
        public static function SessionObject($index, $obj = null) {
            if(isset($obj)) {
                return self::Session($index, serialize($obj));
            }
            
            return unserialize(self::Session ($index));
        }

        /**
         *
         * @param  string $index
         * @param  string $value
         * @return mixed
         */
        public static function Cookies($index = null, $value = null) {
            if(!isset($index)) {
                return filter_input_array(INPUT_COOKIE);
            } elseif(isset($index) && !isset($value) && filter_has_var(INPUT_COOKIE, $index)) {
                return filter_input(INPUT_SERVER, $index);
            } elseif(isset($index) && isset($value) && filter_has_var(INPUT_COOKIE, $index)) {
                setcookie($index, $value);
            }

            return null;
        }

        /**
         * Verifica se houve tentativa de ataque CSRF
         *
         * @param string $sessionIndex
         * @param string $getParameter
         *
         * @return string
         */
        static public function verifyCsrf($sessionIndex, $getParameter = null) {
            @session_start();

            // a obter o token que vai impedir o ataque csrf
            $csrfToken = null !== self::Session($sessionIndex) ? self::Session($sessionIndex) : null;

            // prevenir ataque por cross site request
            if(!isset($getParameter) || $getParameter != $csrfToken) {
                return self::CSRF_MESSAGE;
            }
        }

        /**
         * Define o csrf token em sessão, permitindo assim despistar tentativa de ataque CSRF
         *
         * @param string $index
         */
        static public function setUpCsrfValue($index = "csrf-token") {
            @session_start();

            // definir o csrf
            $csrf = md5(uniqid(mt_rand(), true));

            self::Session($index, $csrf);
        }

        /**
         * httpPostRequest
         *
         * @param  string $url
         * @param  string $query
         * @return string
         */
        public static function httpPostRequest($url, $query){
           /*
           $header  = "POST  " . self::ACCESS_TOKEN_URI .  " HTTP/1.1\r\n" ;
           $header .= "Host: " . self::HOST . "\r\n" ;
           $header .= "Content-string: application/x-www-form-urlencoded\r\n" ;
           $header .= "Content-length: " . strlen($query) . "\r\n" ;
           $header .= urlencode("submit=true&{$query}") . "\r\n";
           $header .= "Connection: close\r\n\r\n" ;

           return header($header);
            */

           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, 1);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           $result = curl_exec($ch);
           curl_close ($ch);

           return $result;
        }

        /**
         * Elimina javascript de uma string
         */
        public static function StripTags() {
            $request = $_REQUEST;

            // eliminar injecção de javascript
            foreach($request as &$value) {
                $temp  = strip_tags($value);
                $value = preg_replace(self::STRIP_SCRIPT, "", $temp);
            }

            unset($value);
        }
    }