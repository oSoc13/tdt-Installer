<?php

/**
 * The index file for the installer.
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

require_once __DIR__.'/vendor/autoload.php';

require_once __DIR__.'/tdt/installer/Installer.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->match('/', function (Request $request) use ($app) {
    
    $getParams = $request->query->all();
    
    if(array_key_exists('step', $getParams)) $step = $getParams['step'];
    else $step = 0;
    
    $installer = new tdt\installer\Installer();
    $result = $installer->executeStep($step);
    
    return $app['twig']->render('template.html', array(
        'result' => $result,
        'nextStep' => $step+1
    ));
})
->method('GET|POST');;

$app->run();
