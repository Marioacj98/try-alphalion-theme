<?php
/* Template Name: Quiz */

//Instanciamos nuestra clase index que es la que mostrara el html
$quiz = new Quiz();
//Llamamos a nuestra funcion render
echo $quiz->render();

class Quiz extends BaseClass
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
        $page_title = get_the_title();
        $content = get_the_content();

        return $this->preRender(
            "pages/quiz.twig",
            array(
                "title" => $page_title,
                "sidebar_class" => "clas de ejemplo",
                "post" => get_post(),
                "cart_total" => $cart_total,
                "acf" => $acf,
                "modules" => get_field('content', get_post()->ID),
                "page_content" => $content
            ),
            $styles,
            $scripts
        );
    }
    
}
