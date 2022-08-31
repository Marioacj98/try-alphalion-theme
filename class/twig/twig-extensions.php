<?php
/**
 * Clase de twig para manejar las extensiones que vamos a utlizar en el twig, por ahora solo usaremos la extension de debug
 * que nos permite usar la funcion dump para imprimir el objeto de que estamos mandando al template
 */
class TwigExtension
{
    /**
     * Funcion que agrega la extension, 
     * Recuerden simpre dar lo que uno recibe es decir recibimos un objeto twig y devolvemos el mismo objeto solo que 
     * con algunas modificaciones
     */
    public function addExtension($twig)
    {
        $twig->addExtension(new Twig_Extension_Debug());

        return $twig;
    }

}

?>