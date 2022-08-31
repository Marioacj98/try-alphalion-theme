<?php

/**
 * Clase que tiene las funciones basicas para la configuracion del theme en el cual tenemos 2 funciones basicas
 * la primera 'addFilters' que es la que agrega las funcionalides filters al tema, y la segunda
 * 'addAjax' donde se listan todas las llamada ajax que queramos hacer en el template
 */
class ConfigurationTheme
{
    /**
     * Agrega los filtros al thema
     */
    function addFilters()
    {        
        //Add filter, primer campo el string del filtro, segundo campo el objeto callable que queremos llamar
        add_filter('acf/settings/save_json', array($this,'acf_json_save_point'));
        add_filter('acf/settings/load_json', array($this,'acf_json_load_point'));
    }

    //Agrega los ajax que se quieran mostrar  llamar
    function addAjax()
    {
        //Instanciamos nuestro manejador de funciones ajax
        $ajax = new ThemeAjax();
        //Por cada ajax que queramos llamar tenemos 2 opciones. La primera usuario que ejecuta la opción este logueado (wp_ajax_) 
        //y la segunda para  cuando el usuario no se encuentre logueado (wp_ajax_nopriv_)
        //dependiendo del caso podemos usar la primera, la segunda o ambas
        //Este action toma 2 variables el primero es el 'action' que indicaremos en el js. Este 'action' es el texto que viene 
        //inmediatamente despues de 'wp_ajax_nopriv_' o de 'wp_ajax_'. La 2da variable es el objeto callable que 
        //queramos ejecutar
        add_action( 'wp_ajax_nopriv_ejemplo', array($ajax, 'ejemploAjax') );
        add_action( 'wp_ajax_ejemplo', array($ajax, 'ejemploAjax') );
    }

    
    //Filtro para guardar los acf en json en el disco duro
    function acf_json_save_point($path)
    {
        $path = get_stylesheet_directory() . '/wordpress_files/acf-json';
        return $path;

    }

    //Filtro para cargar los acf desde el disco duro
    function acf_json_load_point($paths)
    {
        unset($paths[0]);
        $paths[] = get_stylesheet_directory() . '/wordpress_files/acf-json';
        return $paths;

    }

}
?>