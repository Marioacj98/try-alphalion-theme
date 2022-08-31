<?php

/**
 * Clas que agrega las variables globales a twig, lo que incluyan acá será visible a traves de todo el sitio 
 * podemos colocar por ejemplo información del header, footer u direcciones de donde esta el plugin
 */
class TwigGlobals
{

    /**
     * Funcion que agrega las variables globales a twig
     * Recuerden simpre dar lo que uno recibe es decir recibimos un objeto twig y devolvemos el mismo objeto solo que 
     * con algunas modificaciones
     */
    function addGlobals($twig)
    {
        //Creamos un objeto que transporta las variables al  template 
        $globals = new stdClass();
        $globals->charset = "utf-8";
        $globals->description = "Alphalion";
        $globals->theme = array("link" => get_template_directory_uri());
        $globals->author = "Alphalion";
        
        //Agregamos las variables de forma global a nuestro engine de template
        $twig->addGlobal("site", $globals);
        return $twig;
    }
}