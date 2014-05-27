<?php

/**
 * Description of ViewCmsDefault
 *
 * @author Filipe Fernandes
 */

class ViewCmsDefault extends View {
    
    public function init() {
        print($this->data);
        // ler um "container" para agregar toda a lista de medicos
        $this->view = $this->render->load("containers/slideshow.html"); // ler container onde vai ser injecjado o html produzido por listElements
        
        // injectar o html produzido na TAG correspondente do layout
        $this->render->injectIntoLayout("","{BODY}");
    }
}
