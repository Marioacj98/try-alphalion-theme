<?php
/* Template Name: Template de ejemplo */
class TemplateEjemplo extends BaseClass
{
    function render()
    {
        $scripts = array(
                    array('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'),
                    array("ejemplo_ajax", get_template_directory_uri() . "/assets/js/example-ajax.js" ));
        
        $styles = array(array("example", get_template_directory_uri() . "/assets/css/example-style.css"));
        
        $localized = array(array("ejemplo_ajax", "ejemplo", array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )));

        return $this->preRender("template-ejemplo.twig", array(), $styles, $scripts, $localized);
    }

}



if(null === constant("TEST"))
{
    //Instanciamos nuestra clase template
    $templateEjemplo = new TemplateEjemplo();
    //Llamamos  a nuestro metodo Template
    echo $templateEjemplo->render();
}

?>