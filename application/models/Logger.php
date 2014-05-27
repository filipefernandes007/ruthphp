<?php


    /**
     * 
     * Gestão de login.
     *
     * @author Filipe Fernandes
     * 
     */
    class Logger extends Model {
        const SESSION_PATH = "session_save";

        private static $identity;
        private static $instance;

        private function Logger() {
            parent::init();

            $this->table = "users";
            $this->vo    = "UserVO";


            //session_save_path(ROOT . DIRECTORY_SEPARATOR . "session_save" . DIRECTORY_SEPARATOR);
        }

        public static function getInstance() {
            $class = __CLASS__;

            if(!isset(self::$instance)) {
                self::$instance = new $class();
                self::$identity = new UserVO();
            }

            return self::$instance;
        }

        /**
         * 
         * Efectua o login
         * 
         * @param UserVO $user
         * @return UserVO
         */
        public function login($user) {
            //$sql = "SELECT * FROM {$this->table} WHERE username = :username AND password = :password;";
            //$sql = "SELECT * FROM {$this->table} WHERE username = ? AND password = ?;";

            if(!is_object($user)) {
                throw new InvalidArgumentException("Argumento inválido!", 0);
            }

            if(!self::isSHA1($user->password)) {
                $user->password = sha1($user->password);
            }

            $sqlParam = new SqlParam();
            $sqlParam->setVO($user);
            $sqlParam->table = $this->table;
            $sqlParam->where = "username = ? AND password = ?";

            $res = parent::Select($sqlParam);
            //$res = parent::Transaction($sql);

            if($res && sizeof($res) > 0) { 
                self::$identity = clone $res[0];

                //self::$identity->setUsername($res[0]->getUsername());
                //self::$identity->setPassword($res[0]->getPassword());

                self::setSession();

                $res[0]->setPassword(null); // não devolver a password!

                return $res;
            }


            return;
        }

        /**
         * 
         * Destroi a sessão. Este deve ser o método invocado no URL
         */
        public function logout() {
            self::killSession();
        }

        /**
         * 
         * Verifica se há sessão.
         * 
         * @return boolean
         */
        public function isLogged() {
            if(null === RequestHttp::Session("identity")) {
                return false;
            }

            return true;
        }

        /**
         * 
         * Verifica se uma string é uma chave encriptada com o algoritmo sha1.
         * 
         * @param string $pw
         * @return boolean
         */
        private static function isSHA1($pw) {
            if(strlen($pw) == 40 || strlen($pw) == 20) {
                return true;
            }

            return false;
        }

        public function insert(SqlParam $obj) {

        }

        public function delete(SqlParam $obj) {

        }

        public function update(SqlParam $obj) {

        }

        /**
         * 
         * Obtém a identidade do utilizador
         * 
         * @return UserVO
         */
        public function getIdentity() {
            @session_start();

            return RequestHttp::Session('identity');
        }

        /**
         * 
         * Inicializa a sessão
         */
        private function setSession() {
            @session_start();

            $_SESSION['id']       = uniqid();
            $_SESSION['identity'] = clone self::$identity;

            session_regenerate_id(true);   
        }

        /**
         * 
         * Destroi a sessão
         */
        private function killSession() {
            @session_start();

            RequestHttp::Session('identity', null, RequestHttp::_UNSET);
            RequestHttp::Session('id', null, RequestHttp::_UNSET);

            self::$identity = null;

            session_destroy();
        }

        /**
         * 
         */
        private function parseSessionFile() {
            $file = ROOT . DIRECTORY_SEPARATOR . self::SESSION_PATH . DIRECTORY_SEPARATOR . "sess_" . RequestHttp::Cookies("PHPSESSID");

            if(RequestHttp::Cookies("PHPSESSID") && file_exists($file)) {
                $contents = file_get_contents($file);

                @session_start();
                session_decode($contents);
            }
        }
    }
