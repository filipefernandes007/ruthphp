<?php

    /**
     * Description of ATemplate
     *
     * @author ffernandes
     */
    class ATemplate extends Template {
        
        protected function init($args = null) {
            $mp  = new ModelPlanet();
            // carregar o ficheiro de medico: <li data-id="{ID}">...</li>
            $this->render->load("elements/solar-system/planet.html");

            // definir o ValueObject de forma a extrair as propriedades
            $this->render->setVO("PlanetVO"); 
            
            // ler o element
            $this->render->load("elements/solar-system/dropdown-planets.html");
            // renderizar com base na leitura anterior
            $planets = $this->render->listElements($mp->getAll());
            
            // ler o container 
            $this->render->load("containers/solar-system/dropdown-planets.html");
            $dp = $this->render->replace("{LIST}", $planets);

            // injectar o html produzido na TAG correspondente do layout
            $this->render->injectIntoLayout($dp,"{DROPDOWN-PLANETS}");
        }
        
    }
