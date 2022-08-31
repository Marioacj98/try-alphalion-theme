<?php
$currentFolder = str_replace("/wp-content/themes/test-incompatibilidad/test/" . basename(__FILE__), "", __FILE__);
define(ABSPATH, $currentFolder . "/wp/" );
define("TEST", true);

require_once($currentFolder . "/wp/wp-load.php");
require_once($currentFolder . '/wp-content/themes/test-incompatibilidad/vendor/autoload.php');
require_once($currentFolder . '/wp-content/themes/test-incompatibilidad/class/base-class.php');
require_once($currentFolder . '/wp-content/themes/test-incompatibilidad/wp-template/template-ejemplo.php');

use PHPUnit\Framework\TestCase;

final class TestTemplateEjemplo extends TestCase
{
    public function testGetTenPosts(): void
    {
        $ejemploTemplate = new TemplateEjemplo();
        $this->assertEquals(
            1,
            count($ejemploTemplate->getTenPosts())
        );
    }
}