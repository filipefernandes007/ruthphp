<?php

    /**
     * Description of ViewPlanet
     *
     * @author Filipe Fernandes
     */

    class ViewDefault extends View {

        protected function init() {
            //session_destroy();
            $mp = new ModelPlanet();
            
            if(null == RequestHttp::Session('get-all')) {
                $data = $mp->getAll();
                RequestHttp::SessionObject('get-all',$data);
            } else {
                $data = RequestHttp::SessionObject('get-all');
            }
            
            /*
            $this->render->data = $data;
            $this->view = $this->render->render("containers/solar-system/list-planets.tpl");
            */
            $this->data = $data;
            $this->view = "containers/solar-system/list-planets.tpl";
            
            // renderizar a view
            $this->renderView();
            
            // renderizar o layout
            return new Layout(array('view' => $this->view));
        }
    }
