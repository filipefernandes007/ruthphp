<?php

/**
 * Class simples para upload de ficheiros
 * 
 * @author Filipe Fernandes
 * @created 20/06/2013
 */

class Upload {
    
    /**
     * 
     * Efectua o upload de um ficheiro dado um caminho de destino.
     * 
     * @param string $path
     * @return string
     * @throws Exception
     * 
     */
    public function Upload($path) {        
        $filename = uniqid();

        if (!is_writable($path))
            throw new Exception("Sem permissões de escrita: " . fileperms($path), 0);

        if ($_FILES["file"]["error"] > 0)
            throw new Exception("Erro: " . $_FILES["file"]["error"] . "<br/>", 0);
        
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $path . "{$filename}"))
            return $path . $filename;
        else
            throw new Exception("Erro no upload!", 0);
    }

}
