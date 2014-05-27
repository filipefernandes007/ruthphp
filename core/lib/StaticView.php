<?php

/**
 * Cria automaticamente o html para a visualização, o update, a inserção e a remoção de dados
 *
 * @author Filipe
 */

class StaticView {
    //put your code here
    
    public function Display($list, $hidden = NULL) {
        $out = "";
        
        foreach($list as $vo) {
            $props = Debug::getProp($vo); // obter as propriedades
            
            foreach($props as $key => $value) {
                if(in_array($prop, $hidden))
                    $out .= "<div class='hidden' data-field={$key}>{$value}</div>";
                else        
                    $out .= "<div data-field={$key}>{$value}</div>";
            }
        }
        
        print($out);
    }
}
