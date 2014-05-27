<?php

/**
 * Description of ViewPlanet
 *
 * @author Filipe Fernandes
 */

class ViewPlanet extends View {

    protected function init() {
        $mp = new ModelPlanet();

        // estamos a receber dados directamente do URL
        // convém previnir o SQL injection, se bem que estejamos a utilizar PDO
        RequestHttp::StripTags();

        if(is_array($this->data)) {
            $data = $this->data[0];
        } else {
            $res  = $mp->getById((int)$this->data);
            $data = $res[0];
        }

        $ex_images     = explode("|", $data->images);
        $ex_sat        = explode("|", $data->satellites);

        $images     = array();
        $satellites = array();

        foreach($ex_images as $key => $value) {
            $images[$key] = array();
            $images[$key] = explode("#", $value);
        }

        foreach($ex_sat as $key => $value) {
            $satellites[$key] = array();
            $satellites[$key] = explode("#", $value);
        }

        $this->name       = $data->name;
        $this->images     = $images;
        $this->satellites = $satellites;
        $this->view       = "views/planets.tpl";

        // renderizar a view
        $this->renderView();

        // renderizar o layout
        return new LayoutPlanet(array('view' => $this->view, 'data' => $data));
    }
}
