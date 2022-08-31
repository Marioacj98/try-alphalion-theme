<?php

/**
 * Clase que se encarga de manejar las llamadas ajax hacia el wordpress para ver un ejemplo 
 * de como implementar ver template-ejemplo en su version php, twig y js
 */
class ThemeAjax
{
    //Funcion que va a ser llamada cuando se solicite el ajax del action "ejemplo"
    //Ojo que debe ser publica para que wordpress tenga acceso a ella
    public function ejemploAjax()
    {
        //Obtenemos los 5 post publicados 
        $posts = get_posts(array("post_status" => "publish"));
        $response = array();
        if(count($posts) > 0)
        {   
            $response = array("status" => "ok", "data" => $posts);
        }
        else
        {
            $response = array("status" => "error", "error" => "No existen suficientes posts para mostrar" );
        }

        //Y los retornamos como objeto  json
        // Como observacion personal siempre agrego un status que puede ser 'ok' u 'error'
        // para que desde el cliente(js) pueda determinar
        // que hacer sin la necesitada de revisar otra variable
        // adicionalmente si existe un error envio la variable error con un string legible para el usuario
        // entonces dependiendo del status  es lo que hago en el cliente
        wp_send_json(array("status" => "ok", "data" => $posts));
        //El metodo 'wp_send_json' llama automaticamente a la funcion wp_die y die, tambien agrega los headers
        //necesarios para indicar que la respuesta esta  en formato json
    }
}
?>