<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* notfound.twig */
class __TwigTemplate_1a8cad37a38d1ad43b6bbaf546a31d94b0968fde93ae1f917b172e0e84a42b69 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<!DOCTYPE html
        PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
        \"http://www.w3c.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">

<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
    <title>Tuqan</title>
    <meta name=\"Generator\" content=\"Tuqan\" />
    <link rel=\"stylesheet\" href=\"/css/notfound.css\" type=\"text/css\" />
</head>
<body>
<div id=\"clouds\">
    <div class=\"cloud x1\"></div>
    <div class=\"cloud x1_5\"></div>
    <div class=\"cloud x2\"></div>
    <div class=\"cloud x3\"></div>
    <div class=\"cloud x4\"></div>
    <div class=\"cloud x5\"></div>
</div>
<div class='c'>
    <div class='_404'>404</div>
    <hr>
    <div class='_1'>THE PAGE</div>
    <div class='_2'>WAS NOT FOUND</div>
    <a class='btn' href='/'>BACK TO TUQAN</a>
</div>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "notfound.twig";
    }

    public function getDebugInfo()
    {
        return array (  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "notfound.twig", "/var/www/html/templates/notfound.twig");
    }
}
