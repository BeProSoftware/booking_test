<?php
/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Action\HomePageAction::class, 'home');
 * $app->post('/album', App\Action\AlbumCreateAction::class, 'album.create');
 * $app->put('/album/:id', App\Action\AlbumUpdateAction::class, 'album.put');
 * $app->patch('/album/:id', App\Action\AlbumUpdateAction::class, 'album.patch');
 * $app->delete('/album/:id', App\Action\AlbumDeleteAction::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Action\ContactAction::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Action\ContactAction::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

/** @var \Zend\Expressive\Application $app */

$app->get('/', App\Action\BookingPageAction::class, 'home');
$app->get('/bookings', App\Action\BookingPageAction::class, 'bookings');
$app->get('/bookings[/add]', App\Action\BookingPageAction::class, 'bookings.add');
$app->get('/bookings[/edit/:id]', App\Action\BookingPageAction::class, 'bookings.edit');
$app->get('/bookings[/delete/:id]', App\Action\BookingPageAction::class, 'bookings.delete');
//$app->get('/bookings[/{action:add|delete|edit}[/:id]]', App\Action\BookingPageAction::class, 'bookings');
$app->post('/api/ajax', App\Action\AjaxPageAction::class, 'api.ajax');
$app->get('/api/ping', App\Action\PingAction::class, 'api.ping');
