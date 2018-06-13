<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

use Form\BookingForm;

use DbManager\Table;

class AjaxPageAction implements ServerMiddlewareInterface
{
    private $router;

    private $template;

    private $bookingsTable;

    public function __construct(Router\RouterInterface $router = null, Template\TemplateRendererInterface $template = null, Table\BookingsTable $bookingsTable)
    {
        $this->router   = $router;
        $this->template = $template;
		
		$this->bookingsTable = $bookingsTable; // need this for PDO
    }
	
	//both edit and add fucntions come through here via ajax
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
		if (!empty($_POST)) {
			foreach($_POST as $key => $value){
				$data = json_decode($key, true);
			}
			if(empty($data["username"])){
				echo "Please enter a username";
				exit;
			}
			$data["requested_date"] = date("Y-m-d H:i:s", strtotime(str_replace("_", " ", $data["requested_date"]))); // format for the database

			$result = $this->bookingsTable->save($data);
		}
		
		echo 1;
		exit;
	}
	
}
