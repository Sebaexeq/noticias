<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Rutas para el formulario de registro
$routes->get('/registroUser','Home::registroUser');
#$routes->get('/registroExito', 'Form::registroExito');
$routes->post('/registroExito', 'Home::registroExito');

// Rutas para el formulario de inicio de sesión
$routes->get('/inicioExito', 'Form::inicioExito');
$routes->post('/inicioExito', 'Home::inicioExito');
$routes->get('/inicio','Home::inicio');
$routes->get('/logout','Home::logout');

//Rutas para el formulario de usario editor
$routes->get('/crear-noticia','Home::crear');
$routes->post('/crearNoticia', 'Home::crearNoticia');
$routes->get('/ver_noticia', 'Home::verNoticia');
$routes->post('/editarNoticia', 'Home::editarNoticia');
$routes->get('/deshacer', 'Home::deshacer');
$routes->get('/descartar_noticia', 'Home::descartar_noticia');
$routes->get('/enviar_paraValidar', 'Home::enviar_paraValidar');
$routes->get('/enviar_paraBorrador', 'Home::enviar_paraBorrador');
$routes->get('/publicar', 'Home::publicar');
$routes->get('/historial', 'Home::historial');

$routes->get('/mis-noticias','Home::misNoticias');
$routes->get('/validar-noticias','Home::validarNoticias');
$routes->get('/revisar-publicaciones','Home::revisarPublicaciones');
