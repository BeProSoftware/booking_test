# booking_test
Zend Framework 3 Expressive CRUD application with Angular ajax booking form

= Summary =

This code provides the sql and files necessary to setup an installation with AngularJS forms which submit to the Zend Framework for saving to a database. Custom routes, validation (frontend & backend), Ajax (json), PDO (MySQL), and other concepts are tackled within the example. 


= AngularJS =

This example uses ajax (json) calls to post form data and relay any errors. It also ustilizes AngularJS validation rules on the form fields. The Add and Edit forms showcase how to create a blank form as well as a form populated with values.


= Zend Framework 3 Expressive =

This installation utilizes the expressive skeleton provided by the Zend team. Within it, we create 2 unique controllers, BookingPageAction and AjaxPageAction. As the names suggest, the ajax calls are sent to the ajax controller and the CRUD functions are isolated to the Bookings controller.

Validation is setup in the controllers and PDO actions are left to the models (aka factory) for implementation. As expected with MVC frameworks, validated data is passed to the model controller and any errors are relayed back through the controller down to the view. 

Various libraries are utilized to facilitate the functionality including the routing. CRUD and ajax functions have been implemented using the routing. For testing, you can submit data to the "api/ajax" url.

= Conclusion =

This example showcases how to use AngularJS along with the Zend framework from a technical perspective. If interested in an architectural review of these technologies, review the following article

- https://www.beprosoftware.com/blog/zend-framework-3-expressive-with-angularjs/