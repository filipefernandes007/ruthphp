<?php

/**
 * Description of ViewCmsLogin
 *
 * @author Filipe Fernandes
 */

class ViewCmsLogin extends View {
    
    public function init() {
        // ler um "container" para agregar toda a lista de medicos
        $this->view = $this->render->load("containers/login/login.html"); // ler container onde vai ser injecjado o html produzido por listElements
        
        // injectar o html produzido na TAG correspondente do layout
        $this->render->injectIntoLayout($this->render->getContent(),"{BODY}");
    }
}

?>