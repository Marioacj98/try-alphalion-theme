<?php 

/**
 * Clase base para cualquier elemento que imprima una pagina web ya sea single, term, page, template, etc.
 * La clase es abstracta para que lance un error 500 cuando alguna de sus clases hijas no incluya cualquiera de los
 * 3 metodos listados a continuacion
 * addStyle
 * addScript
 * render
 * 
 * adicionalmente podemos encontrar metodos utilitarios disponibles para ser usados por cualquiera de sus hijos como por 
 * ejemplo la funcion getTwigTemplate que retorna el objeto twig para ser utilizado. Tambien podemos encontrar la 
 * funcion pHtml el cual imprime el objeto enviado envuelto en un tag <pre> lo que hace mas legible el array u objeto 
 * que queremos depurar
 */
abstract class BaseClass
{
    //Name of script to deregister
    private $wpScript = ["jquery"];
    //Name of style to deregister
    private $wpStyle = ["jquery"];

    abstract function render();

    /**
     * Agrega el estilo que corresponde a la  página
     */
    function addStyles($styles, $templateVersion)
    {
        $templateVersion = wp_get_theme()->version;
        foreach ($styles as $key => $style) 
        {
            if(in_array($style[0], $this->wpStyle) && count($style) > 1)
            {
                wp_deregister_style($style[0]);
            }
            //Manejador del estilo
            //url del estilo
            //Dependencias (css, bootsrap, etc) , si la dependencia se encuentra listada y no se ha importado, wordpress la importa por ti
            //version del style, en frontent el script se cachea, si no se cambia la version no se va a actualizar para el usuario no logueado
            //en que sector se importa true => header, false => footer
             wp_enqueue_style($style[0], $style[1], array(), $templateVersion, 'all');
        }
    }

    /**
     * Agrega el script que corresponde a la  página
     */
    function addScripts($scripts, $templateVersion)
    {
        $templateVersion = wp_get_theme()->version;
        foreach ($scripts as $key => $script) 
        {
            if(in_array($script[0], $this->wpScript) && count($script) > 1)
            {
                wp_deregister_script($script[0]);
            }
            wp_enqueue_script($script[0], $script[1], array(), $templateVersion, true);
        }
    }

    /**
     * Agrega el js que quieran agregar al script
     */
    function localizeScript($scripts)
    {
        foreach ($scripts as $key => $script) 
        {
            wp_localize_script( $script[0], $script[1], $script[2]);
        }
    }

    function preRender($templateName, $dataToSend = array(), $styles = array(), $scripts = array(), $localized = array(), $global = array())
    {
        $templateVersion = wp_get_theme()->version;
        if(count($styles) > 0)
        {
            $this->addStyles($styles, $templateVersion);
        }
        if(count($scripts) > 0)
        {
            $this->addScripts($scripts, $templateVersion);
        }
        if(count($localized) > 0)
        {
            $this->localizeScript($localized);
        }

        //$dataToSend['header_menu'] = $this->getNavMenuByName('header-menu');
        //$dataToSend['thumbnail'] = get_the_post_thumbnail_url( get_post()->ID );

        $twigEngine = $this->getTwigTemplate();

        return $twigEngine->render($templateName, $dataToSend);
    }

    public function getNavMenuByName($MenuName)
    {
        $locations = get_nav_menu_locations();
        $menu = wp_get_nav_menu_object( $locations[ $MenuName ] );
        $items = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );

        $newMenu = [];

        foreach ($items as $key => $value) {
            if($value->menu_item_parent == 0){
                $newMenu[$value->ID] = (array)$value;
            } else {
                $newMenu[$value->menu_item_parent]['child'][$value->ID] = $value;
            }
        }

        return $newMenu;
    }

    function pHtml($object)
    {
        print_r("<pre>");
        print_r($object);
        print_r("</pre>");
    }

    function getTwigTemplate()
    {
        require_once get_template_directory() . '/vendor/autoload.php';
        $loader = new Twig_Loader_Filesystem(get_template_directory() . '/template/');
        $twigFunction = new TwigFunction();
        $twigExtension = new TwigExtension();
        $twigGlobals = new TwigGlobals();
        
        $twig = new Twig_Environment($loader, array(
            'debug' => true,
        ));
        
        
        $twig  = $twigGlobals->addGlobals($twig);
        $twig  = $twigFunction->addFunctions($twig);
        $twig  = $twigExtension->addExtension($twig);
        
        return $twig;
    }

    public function getFieldsById($id) {
        return get_fields( $id );
    }
}
?>