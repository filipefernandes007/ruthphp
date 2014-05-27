<?php

/**
 * Description of ViewMedico
 *
 * @author Filipe Fernandes
 */

class ViewUpload extends View {
    protected function init() {
        
        // ler um "container" para agregar toda a lista de medicos
        $this->render->load("containers/upload.html"); // ler container onde vai ser injecjado o html produzido por listElements


        // injectar o html produzido na TAG correspondente do layout
        $this->render->injectIntoLayout($this->render->getContent(),"{BODY}");
        
    }
}
