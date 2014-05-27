<?php
    /**
     * 
     */
    class LayoutPlanet extends ATemplate {
        public function init($args = null) {
            parent::init($args);
            
            // ler container onde vai ser injecjado o html produzido por listElements
            $this->render->loadContainer("solar-system/list-planets.html"); 
            // carregado o ficheiro, substituir em CONTENT pelo que foi obtido em $args['view']
            $list = $this->render->replace("{CONTENT}", $args['view']);
            
            // injectar no layout
            $this->render->injectIntoLayout($list);
        }
    }