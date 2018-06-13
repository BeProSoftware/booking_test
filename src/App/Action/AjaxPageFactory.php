<?php

namespace App\Action;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

use DbManager\Table;

class AjaxPageFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $router   = $container->get(RouterInterface::class);
        $template = $container->has(TemplateRendererInterface::class)? $container->get(TemplateRendererInterface::class) : null;

	    $bookingsTable = $container->get( Table\BookingsTable::class );
	
        return new AjaxPageAction($router, $template, $bookingsTable);
    }
}
