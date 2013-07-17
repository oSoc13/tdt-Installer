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
    // Get the current page number
    $getParams = $request->query->all();
    if(array_key_exists('page', $getParams)) $step = (int) $getParams['page'];
    else $step = 0;
    
    // Check if the user is allowed to visit this page
    if(!$app['session']->get('requirementsOk')) {
        // You cannot start the installer if you don't meet the requirements!
        $step = 0;
    } elseif($step > count($wizardSteps) - 1 || $step < 0) {
        // You cannot go to a non-existing page of the installer!
        if($app['session']->get('dbinstalldefault') !== null) {
            $step = $app['session']->get('lastVisitedStep');
        } else {
            $step = 0;
        }
    } elseif($step > $app['session']->get('lastVisitedStep') + 1) {
        // You cannot skip steps in the installer, unless you have
        // chosen the default database installation
        if($app['session']->get('dbinstalldefault') !== true) {
            $step = $app['session']->get('lastVisitedStep');
        }
    } elseif($step < 4) {
        // You cannot revisit the package download pages
        if($app['session']->get('lastVisitedStep') > $step) {
            $step = $app['session']->get('lastVisitedStep');
        }
    }
    
    $app['session']->set('lastVisitedStep', $step);
    
    $page = strtolower($wizardSteps[$step].'.html');
    $className = 'tdt\\installer\\wizardsteps\\' . $wizardSteps[$step];
    $class = new $className();
    
    $pagevariables = array();
    $pagevariables['validationError'] = false;
    
    if($request->getMethod() == 'POST') {
        $class->writeData($request, $app['session']);
        $validationOutput = $class->validate($request);
        
        if($validationOutput !== true) {
            $pagevariables = array_merge($pagevariables, $validationOutput);
            $pagevariables['validationError'] = true;
        } else {
            // Go to finish if we choose a default database installation
            $redirectPage = $app['session']->get('dbinstalldefault') === true ? count($wizardSteps) - 1 : ($step + 1);
            
            return $app->redirect('?page='.$redirectPage);
        }
    }
    
    $pagevariables['currentpage'] = $step;
    $pagevariables['hasnextpage'] = $step < count($wizardSteps);
    $pagevariables = array_merge($pagevariables, $class->getPageContent($app['session']));
    
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
