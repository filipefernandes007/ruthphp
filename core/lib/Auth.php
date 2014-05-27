<?php

/*
 * Class que faz o parse das autorizações associadas aos métodos de cada classe derivada de Model
 */

    /**
     * Valida as credenciais para um método de uma classe: se exige sessão, e se assim for quem tem autorização de o utilizar
     *
     * @author Filipe Fernandes
     */

    class Auth {
        const ALLOW_RAW = 'allowRaw';
        const SESSION   = 'Session';
        
        protected $class; // o nome da classe
        protected $allwaysAllowRaw = false;

        public function Auth() {

        }

        /**
         * Define a classe face à qual será aplicado o ReflectionClass
         * 
         * @param string $class
         * @return Auth
         */

        public function setClass($class) {
            $this->class = $class;
            
            return $this;
        }
        
        /**
         * Define se para todo o ambiente de desenvolvimento é permitido raw,
         * o que torna superfulo método allowRaw
         * 
         * @param boolean $bool
         * @return Auth
         */
        public function setAllwaysAllowRaw($bool = true) {
            $this->allwaysAllowRaw = $bool;
            
            return $this;
        }
        
        /**
         * 
         * @param string $method
         * @return boolean
         */
        public function allowRaw($method) {
            // em desenvolvimento podemos sempre ver o raw caso assim seja determinado em config.ini
            if($this->allwaysAllowRaw)
                return true;
            
            if($method == "init")
                return true;

            $striped = $this->stripComment($method);
            
            if($this->containsExpression($striped, self::ALLOW_RAW)) {
                //return true;
                
                $stage = $this->stringBetween($striped, 'allowRaw(', ')');
                
                return Config::init()->Stage() == $stage;
            }
            
            return false;
        }

        /**
         * 
         * Verifica se a sessão é exigida para um método
         * 
         * @param string $method
         * @return boolean
         */

        public function isSessionRequired($method) {
            $striped = $this->stripComment($method);
            
            return $this->containsExpression($striped, self::SESSION);
        }

        /**
         * 
         * Obtém os roles autorizados para um método
         * 
         * @param string $method
         * @return array
         */

        protected function GetRoles($method) {
            $striped = $this->stripComment($method);
            $string  = $this->stringBetween($striped, 'Roles(', ')');
            
            if(!empty($string)) {
                return explode(",", $string);
            }
            
            return null;
        }

        /**
         * 
         * Verifica se há autorização para o método
         *  
         * @param string $method
         * @return boolean
         */

        public function isAuthorized($method) {
            $logger      = Logger::getInstance();
            $logged      = $logger->isLogged();
            $needSession = $this->isSessionRequired($method);
            
            if(!$this->allowRaw($method)) {
                return false;
            }

            if(!$logged && $needSession) { // se não está logado (se não está quebra logo) e pede sessão, deve ser impedido
                return false;
            }
            
            $roles = $this->GetRoles($method);
            
            if(sizeof($roles) == 0) // todos acedem à página
                return true;
            elseif($logged && in_array((string)$logger->getIdentity()->getRole(), $roles))
                return true;

            return false;
        }

        /**
         * @todo Verificar se deve existir para um determinado contexto
         */

        public function isInContext() {
            print(URL);
            $parts = explode('/', URL);
            $last  = end($parts);

            var_dump($parts);
            die();
        }

        /**
         * @param string $method
         * @return string
         */

        protected function stripComment($method) {
            $rc = new ReflectionClass($this->class);
            $s  = "";

            if(is_string($method)) {
                //$x = trim(str_replace(array('/', '*'), '', substr($rc->getMethod("init")->getDocComment(), 0, strpos($rc->getMethod("init")->getDocComment(), '@'))));

                try {
                    $s = $rc->getMethod($method)->getDocComment();
                } catch(ReflectionException $e) {
                    throw new ReflectionException("Erro a reflectir a classe!", 0);
                }

                $s = str_replace('/*', '', $s);
                $s = str_replace('*/', '', $s);
                $s = str_replace('*', '', $s); 
            }

            return $s;
        }

        /**
         * 
         * Remove o último parentesis recto
         * 
         * @param array $array
         * @param string $bracket
         */

        private function removeLastBracket(&$array, $bracket) {
            foreach($array as $key => &$value) {
                $value = str_replace($bracket, "", trim($value));
            }

            unset($value);
        }
        
        /**
         * Se o comentário contém uma determinada expressão
         * 
         * @param  string $string
         * @param  string $value
         * @return boolean
         */
        private function containsExpression($string, $value) {
            if (strpos($string,$value) !== false) {
                return true;
            }
            
            return false;
        }
        
        /**
         * 
         * @param string $string
         * @param string $start
         * @param string $end
         * @return string
         */
        private function stringBetween($string, $start, $end){
            $string = " ".$string;
            $ini    = strpos($string,$start);
            
            if ($ini == 0)
                return "";
            
            $ini += strlen($start);
            $len  = strpos($string, $end, $ini) - $ini;
            
            return substr($string, $ini, $len);
        }


    }