<?php

    /**
     * Base para View. Todas as View's devem derivar de View.
     *
     * @author Filipe Fernandes
     *
     * @abstract
     */

    abstract class View extends stdClass {
        public $view;
        public $render;

        protected $element;
        protected $container;
        protected $display;
        protected $data;

        public function View($value = null) {
            $this->render   = RenderTemplate::getInstance();
            $this->data     = $value;
            $this->display  = true;

            $this->init();
        }

        abstract protected function init();

        /**
         *
         */
        public function renderView() {
            if(isset($this->view)) {
                ob_start();
                //extract($this->data, EXTR_SKIP);
                include_once($this->view);
                $this->view = ob_get_contents();
                ob_end_clean();
                //$this->view = ob_get_clean();
                //ob_end_flush();
            }
        }

        protected function loadContainer() {
            // carregar o ficheiro de medico: <li data-id="{ID}">...</li>
            $this->render->load($this->container);
        }

        protected function loadElement() {
            $this->render->load($this->element);
        }

        public function setDisplay($bool = true) {
            $this->display = $bool;
        }

        public function getDisplay() {
            return $this->display;
        }
    }
