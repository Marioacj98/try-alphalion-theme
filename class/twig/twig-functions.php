<?php

/**
 * Clase utilizada para agregar funciones twig para luego ser llamadas por el template
 * recuerden que todo lo que se instancia acá queda en memoria por ende asegurense que sean funciones
 * que se utlizan siempre por ejemplo el header y footer. 
 * Otro punto a tomar en cuenta que en esta funciones Wordpress imprime directamente por ende no podemos llamarla desde 
 * nuestras clases.
 */
class TwigFunction
{
     /**
     * Funcion que agrega funcionalidades a twig  
     * Recuerden simpre dar lo que uno recibe es decir recibimos un objeto twig y devolvemos el mismo objeto solo que 
     * con algunas modificaciones
     */
    public function addFunctions($twig)
    {
        //creamos el objeto tipo TwigFunction le asignamos el nombre que va a tener en twig y adicionalmente asignamos un 
        // objeto callable, en el caso de php es un arreglo donde el primer parametro es el objeto en si (this) , y el 
        //segundo parametro es el nombre de la funcion en formato string
        $wpHead = new \Twig\TwigFunction('wp_head', array($this, "wpHead"));
        $wpFooter = new \Twig\TwigFunction('wp_footer', array($this, "wpFooter"));
        $getTemplateUrl = new \Twig\TwigFunction('get_template_url', array($this, "getTemplateUrl"));
        $attachment_url = new \Twig\TwigFunction('attachment_url', array($this, "attachment_url"));
        $custom_get_product = new \Twig\TwigFunction('custom_get_product', array($this, "custom_get_product"));
        $attachment_ids = new \Twig\TwigFunction('attachment_ids', array($this, "attachment_ids"));
        $get_variations = new \Twig\TwigFunction('get_variations', array($this, "get_variations"));
        $get_product_price = new \Twig\TwigFunction('get_product_price', array($this, "get_product_price"));
        $do_shortcode = new \Twig\TwigFunction('do_shortcode', array($this, "do_shortcode"));
        $base_site_url = new \Twig\TwigFunction('base_site_url', array($this, "base_site_url"));




        //Agregamos la funcion a twig
        $twig->addFunction($wpHead);
        $twig->addFunction($wpFooter);
        $twig->addFunction($getTemplateUrl);
        $twig->addFunction($attachment_url);
        $twig->addFunction($custom_get_product);
        $twig->addFunction($attachment_ids);
        $twig->addFunction($get_variations);
        $twig->addFunction($get_product_price);
        $twig->addFunction($do_shortcode);
        $twig->addFunction($base_site_url);

        return $twig;
    }

    
    /**
     * Funcion que imprime el header de wordpress
     */
    function wpHead() 
    {
        wp_head();
    }

    /**
     * Funcion que imprime el footer de wordpress
     */
    function wpFooter()
    {
        wp_footer();
    }

    function base_site_url() {
        return get_site_url();
    }

    function getTemplateUrl() {
        get_template_directory_uri();
    }

    function attachment_url($id) {
        return wp_get_attachment_url($id);
    }

    function custom_get_product($id) {
        wc_get_product( $id );
    }

    function attachment_ids($id) {
        $product = wc_get_product( $id );
        return $product->get_gallery_image_ids();
    }

    function get_variations($id) {
        $product = wc_get_product( $id );
        return $product->get_available_variations();
    }

    function get_product_price($id) {
        $product = wc_get_product( $id );
        return $product->get_price();
    }

    function do_shortcode($val) {
        return do_shortcode($val);
    }
}
?>
