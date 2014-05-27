<?php

/**
 * A view que define qual a view onde são mostradas as excepções
 *
 * @author Filipe
 * 
 */

class ViewException extends View {
    
    protected function init() {
        //$this->view = $this->render->freeLoad(ROOT . "public/containers/exception/exception.html");
        $layout = $this->render->freeLoad(ROOT . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "layout" . DIRECTORY_SEPARATOR . "exception.tpl");
        $this->render->setLayout($layout);
        
        $this->render->freeLoad(ROOT . "public" . DIRECTORY_SEPARATOR . "containers" . DIRECTORY_SEPARATOR . "exception" . DIRECTORY_SEPARATOR . "exception.html");
        
        $this->render->replace("{MESSAGE}", $this->data);
        
        // injectar o html produzido na TAG correspondente do layout
        $this->render->injectIntoLayout(strip_tags($this->data),"{TITLE}");
        $this->render->injectIntoLayout($this->render->getContent(),"{BODY}");
        
        $this->render->display();
    }
}
