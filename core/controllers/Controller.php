<?php

    /**
     * O Controller, que é único, efectua o Autoload, instancia Credentials para parse do ficheiro ini, define o stage, corre o RPC e renderiza a
     */
    class Controller {
        protected $_class;
        protected $_type;
        protected $_args;
        protected $_view;
        protected $_msg;

        protected $class;
        protected $method;
        
        protected $auth;
        protected $rpc;
        protected $result;

        /**
         * O controlador!
         *
         * @param type $module
         */
        public function Controller($module = NULL) {
            $this->autoLoad();

            Config::init();          // obter as credenciais
            MasterException::init(); // inicializar as excepções

            $this->auth = new Auth();
            $this->rpc  = new RPC();
            
            if(Config::init()->Raw()) {
                $this->auth->setAllwaysAllowRaw();
            }
        }

        /**
         *
         * Depois de instanciar o Controller, é preciso correr run()
         *
         * @return null|void
         * @throws Exception
         * @throws ReflectionException
         */
        public function run() {
            $this->extractRequest();

            $this->resultMethodCall();

            // se for chamada assíncrona
            if ($this->isAsynchronousRequest()) {
                if(is_array($res)) {
                    print_r($res);
                } else {
                    print($res);
                }
                
                return; // terminar para devolver o resultado AJAX
            }
            
            $this->evaluateEmptyQuery();
            $this->evaluateViewExistence();
            $this->evaluateRawRequest();
        }

        /****************************************************************************************************/
        /* Funções Auxiliares                                                                               */
        /****************************************************************************************************/
        
        /**
         * Efectuar o autoload das classes e pela ordem correcta
         */
        private function autoLoad() {
            $loader = new AutoLoad();
            
            $loader->autoLoader(CORE_LIB);

            // NOTA:
            // O seguinte código é essencial para evitar os require_onces nas classes definidas em core.
            // É também uma forma de evitar erros de caminho nos require's.
            // Feito isto, não se deve acrescentar require_once do que estiver em core!
            $loader->autoLoader(CORE); // agora sim, podemos carregar tudo!
            $loader->autoLoader(APPLICATION);
            
            // ler os modulos
            if(isset($module)) {
                $loader->loadModule(MODULES . DIRECTORY_SEPARATOR . $module);
            }
        }

        /**
         * Verificar se há permissões para invocar o método pedido, se é que foi pedido um método, e obter o respectivo resultado
         * 
         * @return mixed
         * @throws Exception
         */
        private function resultMethodCall() {
            $this->result = null;
            
            if($this->setClassAndMethod()) {
                $this->auth->setClass($this->_class);

                //$this->auth->isInContext();

                if($this->auth->isAuthorized($this->_method)) {
                    $this->result  = $this->rpc->call($this->_class, $this->_method, $this->_args, $this->_type);
                } else {
                    throw new Exception("Não autorizado!", 0);
                }
            }
            
            return $this->result;
        }
        
        /**
         * Avaliar a query no url
         * 
         * @throws ReflectionException
         * @throws Exception
         */
        private function evaluateEmptyQuery() {
            // se não há query no url, nem view definida
            if($this->isQueryEmpty()) {
                // verificar se existe viewdefault
                try {
                    $viewClass = "ViewDefault";
                    $class     = new ReflectionClass($viewClass);
                } catch(ReflectionException $e) {
                    throw new ReflectionException("<i>View Default</i> não definida! " . $e->getMessage());
                }

                $this->auth->setClass($viewClass);

                if(!$this->auth->isAuthorized("init")) {
                    throw new Exception("Não autorizado!", 0);
                }

                $_view = $class->newInstance($this->result);

                if($_view->getDisplay()) {
                    $_view->render->display();
                }

                exit();
            }
        }
        
        /**
         * Avaliar se há uma view para renderizar
         * 
         * @throws Exception
         */
        private function evaluateViewExistence() {
            // se existe view
            if(isset($this->_view) && class_exists($this->_view, true)) {
                $this->auth->setClass($this->_view);

                if(!$this->auth->isAuthorized("init")) {
                    throw new Exception("Não autorizado!", 0);
                }

                if(isset($this->result)) { // dar prioridade aos resultados de RPC
                    $_view = new $this->_view($this->result);
                } elseif(isset($this->_msg)) {
                    $_view = new $this->_view($this->_msg);
                } elseif(isset ($this->_args)) {
                    $_view = new $this->_view($this->_args);
                } else {
                    $_view = new $this->_view(null);
                }

                if($_view->getDisplay()) {
                    $_view->render->display();
                }

                exit();
            }
        }
        
        /**
         * Avaliar se é um pedido com resultados raw
         * 
         */
        private function evaluateRawRequest() {
            if(!isset($this->result )) {
                print("Nada para mostrar");
            }
            
            if(is_array($this->result)) {
                print_r($this->result);
            } else {
                print($this->result);
            }
            
            die;
        }
        
        /**
         * Extrai o que vem de $_GET
         */
        private function extractRequest() {
            RequestHttp::StripTags();

            $this->_call = RequestHttp::Get('call');
            $this->_type = RequestHttp::Get('type');
            $this->_args = RequestHttp::Get('args');
            $this->_msg  = RequestHttp::Get('msg');

            if(null !== RequestHttp::Get('view')) {
                $this->_view = null !== RequestHttp::Get('view') ? RequestHttp::Get('view') : null;
            }
        }

        /**
         *
         * Define a view que é mostrada por default
         *
         * @param  View $_view
         * @return View
         */

        public function setViewDefault($_view) {
            $this->_view = $_view;
        }

        /**
         *
         * Extrai a classe e o método presentes na query url.
         * Retorna um booleano de acordo com o resultado da extracção: true se havia método e classe; 
         *
         * @return boolean
         */

        protected function setClassAndMethod() {
            if(isset($this->_call)) {
                $pieces = explode(".", $this->_call);
                $size   = sizeof($pieces);

                if($size > 1) {
                    $this->_class  = $pieces[0];
                    $this->_method = $pieces[1];

                    return true;
                }
            }

            return false;
        }
        
        /**
         * 
         * @return boolean
         */
        private function isAsynchronousRequest() {
            return null !== RequestHttp::Server('HTTP_X_REQUESTED_WITH') && strcasecmp('XMLHttpRequest', RequestHttp::Server('HTTP_X_REQUESTED_WITH')) === 0;
        }
        
        /**
         * 
         * @return boolean
         */
        private function isQueryEmpty() {
            return sizeof(RequestHttp::Get()) == 0 && sizeof(RequestHttp::Post()) == 0;
        }

    }
