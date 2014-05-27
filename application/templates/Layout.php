<?php

    /**
     *
     */
    class Layout extends ATemplate {

        protected function init($args = null) {
            parent::init($args);

            $this->render->injectIntoLayout($args['view']);
        }
    }