<?php

/**
 * Efectua o Upload de uma imagem
 *
 * @author Filipe
 */

if (!defined("IMAGES_PATH"))
    define("IMAGES_PATH", "images" . DIRECTORY_SEPARATOR);

class UploadImage {

    public function UploadImage() {
        
    }

    /**
     * 
     * Efectua o upload de uma imagem
     * 
     * @param string $field_form
     * @return string
     */
    
    public function upload() {
        //date_default_timezone_set('Europe/Lisbon');
        $upload = new Upload(IMAGES_PATH);
    }

    /**
     * 
     * Efectua o upload de uma imagem, e faz o resize da mesma
     * 
     * @param string $field_form
     * @return string
     * @todo 
     */
    
    public function uploadAndResize($field_form, $size) {
        
    }

}
