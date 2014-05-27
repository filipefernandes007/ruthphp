<?php

    /**
     * Description of Template
     *
     * @author ffernandes
     */
    abstract class Template {
        /**
          * @var RenderTemplate $render
          */
        protected $render;

        public function __construct($args = null) {
            $this->render = RenderTemplate::getInstance();
            
            $this->init($args);
        }

        abstract protected function init($args = null);
    }
