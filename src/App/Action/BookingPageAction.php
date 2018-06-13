<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

use Form\BookingForm;

use DbManager\Table;

class BookingPageAction implements ServerMiddlewareInterface
{
    private $router;

    private $template;

    private $bookingsTable;

    public function __construct(Router\RouterInterface $router = null, Template\TemplateRendererInterface $template = null, Table\BookingsTable $bookingsTable)
    {
        $this->router   = $router;
        $this->template = $template;
		
		$this->bookingsTable = $bookingsTable;
    }

    public function addAction()
    {
		return new HtmlResponse($this->template->render('app::booking-add'));
    }

    public function editAction(ServerRequestInterface $request)
    {
		$id = $request->getAttribute('id', false);
		$bookings = $this->bookingsTable->fetchWhere(array("id" => $id));
		
		return new HtmlResponse($this->template->render('app::booking-edit', array("bookings" => $bookings)));
    }

    public function deleteAction(ServerRequestInterface $request)
	{
		$id = $request->getAttribute('id', false);
		try{
			$this->bookingsTable->delete(array("id", $id));
		}catch(Exception $e){}
		//header('Location: http://bookings.localhost');
		//return $this->redirect()->toRoute('bookings');
		return new RedirectResponse('/bookings');
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
		switch (true) {
            case strstr($_SERVER["REQUEST_URI"],'/bookings/delete'):
				$id = $request->getAttribute('id', false);
				if (! $id) 
					throw new \InvalidArgumentException('id parameter must be provided');
				

                return $this->deleteAction($request);
            case strstr($_SERVER["REQUEST_URI"],'/bookings/add'):
                return $this->addAction();
            case strstr($_SERVER["REQUEST_URI"],'/bookings/edit'):
				$id = $request->getAttribute('id', false);
				if (! $id) 
					throw new \InvalidArgumentException('id parameter must be provided');
				
                return $this->editAction($request);
            default:
        }

        if (! $this->template) {
            return new JsonResponse([
                'welcome' => 'Congratulations! You have installed the Bookings Test application based on Zend Expressive.',
                'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
            ]);
        }
		
		
		$bookings = $this->bookingsTable->fetchAll();
		
		$data = [];
		
		$data["bookings"] = $bookings;

        return new HtmlResponse($this->template->render('app::booking-page', $data));
    }
	
	 public function getForm()  
    {  
        $title = new Zend_Form_Element_Text('Username');  
        $title->setLabel('Username')  
            ->setDescription('Add Username')  
            ->setRequired(true) // required field  
            ->addValidator('StringLength', false, array(10, 120)) // min 10 max 120  
            ->addFilters(array('StringTrim'));
        $reason = new Zend_Form_Element_Select('Reason');  
        $reason->setLabel('reason')  
            ->setDescription('Select the reason')  
            ->setRequired(true)  
            ->setMultiOptions(array(  
                '' => ':: Select a reason',  
                'php' => 'PHP',  
                'database' => 'Database',  
                'zf' => 'Zend Framework'   
            ))  
            ->addFilters(array('StringToLower', 'StringTrim')); // force to lowercase and trim
        $requested_date = new Zend_Form_Element_Text('requested_date');  
        $requested_date->setLabel('Post')  
            ->setRequired(true)  
            ->setDescription('Your date')
            ->addFilters(array('HtmlEntities')); // remove HTML tags
        $submit = new Zend_Form_Element_Submit('submit');  
        $submit->setLabel('Post to Blog') // the button's value  
            ->setIgnore(true); // very usefull -> it will be ignored before insertion
			
        $form = new Zend_Form();  
        $form->addElements(array($username, $reason, $requested_date, $submit));  
            // ->setAction('') // you can set your action. We will let blank, to send the request to the same action
        return $form; // return the form  
    }  
}
