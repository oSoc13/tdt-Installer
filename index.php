<?php

/**
 * The index file for the installer.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/vendor/autoload.php';
$loader->add('tdt\\installer\\', __DIR__.'/src');

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\SessionServiceProvider());
/*$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));*/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/frontend',
));

$app->before(function (Request $request) {
    $request->getSession()->start();
});

$wizardSteps = array('Requirements', 'InitialDownload', 'Packages', 'PackageDownload',
    'General', 'Host', 'Logging', 'Cache', 'Database', 'DatabaseAdvanced', 'DatabaseUser',
    'DatabaseDb', 'Finish');

$app->match('/', function (Request $request) use ($app, $wizardSteps) {
    $getParams = $request->query->all();
    if(array_key_exists('page', $getParams)) $step = $getParams['page'];
    else $step = 0;
    
    $page = strtolower($wizardSteps[$step].'.html');
    $className = 'tdt\\installer\\wizardsteps\\' . $wizardSteps[$step];
    $class = new $className();
    
    $pagevariables = array();
    $pagevariables['currentpage'] = $step;
    $pagevariables['hasnextpage'] = $step <= count($wizardSteps) - 1;
    $pagevariables = array_merge($pagevariables, $class->getPageContent($app['session']));
    
    if($request->getMethod() == 'POST') {
        $class->writeData($request, $app['session']);
        
        $redirectPage = $app['session']->get('dbinstalldefault') === true ? count($wizardSteps) - 1 : ($step + 1);
        
        return $app->redirect('?page='.$redirectPage);
    }
    
    return $app['twig']->render($page, $pagevariables);
});

$app->get('/requirements', function () use ($app) {
    $requirementCheck = new tdt\installer\RequirementsCheck();
    
    return $app->json($requirementCheck->getResult($app['session']));
});

$app->get('/gitclone', function () use ($app) {
    $gitcloner = new tdt\installer\GitCloner();
    
    return $app->json($gitcloner->getResult());
});

$app->get('/packagedownload', function () use ($app) {
    $packageDownloader = new tdt\installer\PackageDownloader();
    
    return $packageDownloader->start($app['session']);
});

$app->post('/packageselection', function (Request $request) use ($app) {
    $packageSelection = new tdt\installer\PackageSelection();
    
    $packages = array();
    
    foreach($request->get('packages') as $package)
    {
        $packages[] = $package;
    }
    
    $packageSelection->writeData($packages);
    
    return $app->redirect('?page=3');
});

$app->run();
