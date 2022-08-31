<?php
/* Template Name: Upsell */

//Instanciamos nuestra clase index que es la que mostrara el html
$upsell = new Upsell();
//Llamamos a nuestra funcion render
echo $upsell->render();

class Upsell extends BaseClass
{
    function render()
    {
        $scripts = array(
            array("plugins-js", get_template_directory_uri() . "/dist/js/sources.js" ),
            array("main-js", get_template_directory_uri() . "/dist/js/main.js" ),
        );

        $styles = array(
            array("plugins-css", get_template_directory_uri() . "/dist/css/plugins.css"),
            array("main-css", get_template_directory_uri() . "/dist/css/main.css")
        );

        global $woocommerce;
        $cart_total = $woocommerce->cart->get_cart_total();
        $acf = get_fields(get_the_ID());

        return $this->preRender(
            "pages/upsell.twig",
            array(
                "title" => "HOLA",
                "sidebar_class" => "clas de ejemplo",
                "post" => get_post(),
                "cart_total" => $cart_total,
                "acf" => $acf,
                "modules" => get_field('content', get_post()->ID)
            ),
            $styles,
            $scripts
        );
    }
    
}
