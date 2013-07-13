<?php

/**
 * The index file for the installer.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\FormServiceProvider;

$loader = require_once __DIR__.'/vendor/autoload.php';
$loader->add('tdt\\installer\\', __DIR__.'/src');

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->before(function (Request $request) {
    $request->getSession()->start();
});

$app->get('/', function (Request $request) use ($app) {
    
    $getParams = $request->query->all();
    
    if(array_key_exists('step', $getParams)) $step = $getParams['step'];
    else $step = 1;
    
    $page ="step.html";
    
    return $app['twig']->render($page, array(
        'currentStep' => $step
    ));
});

//TODO The /config and /db routes have the same structure, so it should be possible
// to put the functionality in a seperate class/method
$app->match('/config', function (Request $request) use ($app) {
   
    $elementBuilder = new tdt\installer\GeneralSettingsElementBuilder();
     
    $getParams = $request->query->all();
    
    if(array_key_exists('step', $getParams)) $step = $getParams['step'];
    else $step = 1;
    
    $page = "configtemplate.html";
    
    $formBuilder = $app['form.factory']->createBuilder('form');
    $elementBuilder->addElements($step, $formBuilder);
    $form = $formBuilder->getForm();
    
    
    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $settingsWriter = new tdt\installer\SettingsWriter();
            $settingsWriter->writeData($data, $app['session']);
            
            if($step + 1 > $elementBuilder::numberOfSteps) $redirectPage = 'db';
            else $redirectPage = 'config?step='.($step + 1);

            return $app->redirect($redirectPage);
        }
    }
        
    return $app['twig']->render($page, array(
        'form' => $form->createView(),
        'currentStep' => $step
    ));
});

$app->match('/db', function (Request $request) use ($app) {

    $elementBuilder = new tdt\installer\DatabaseSettingsElementBuilder();
     
    $getParams = $request->query->all();
    
    if(array_key_exists('step', $getParams)) $step = $getParams['step'];
    else $step = 1;
    
    $page = "dbtemplate.html";
    
    $formBuilder = $app['form.factory']->createBuilder('form');
    $elementBuilder->addElements($step, $formBuilder, $app['session']);
    $form = $formBuilder->getForm();
    
    
    if ('POST' == $request->getMethod()) {
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $settingsWriter = new tdt\installer\SettingsWriter();
            $settingsWriter->writeData($data, $app['session']);
            
            if($step + 1 > $elementBuilder::numberOfSteps || $app['session']->get('dbinstalldefault')) {
                $redirectPage = 'finish';
            } else {
                $redirectPage = 'db?step='.($step + 1);
            }

            return $app->redirect($redirectPage);
        }
    }
        
    return $app['twig']->render($page, array(
        'form' => $form->createView(),
        'currentStep' => $step
    ));
});

$app->get('/finish', function() use ($app) {
    $generalSettingsWriter = new tdt\installer\GeneralSettingsWriter();
    $generalSettingsWriter->writeGeneralData($app['session']);
    
    $dbSettingsWriter = new tdt\installer\DatabaseSettingsWriter();
    $dbSettingsWriter->writeDatabaseData($app['session']);
    
    $settingsCommitter = new tdt\installer\SettingsCommitter();
    $settingsCommitter->commit($app['session']);
    
    return "Finished";
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
    $packageDownload = new tdt\installer\PackageDownload();
    
    return $packageDownload->start($app['session']);
});

$app->post('/packageselection', function (Request $request) {
    $packageSelection = new tdt\installer\PackageSelection();
    
    $packages = array();
    
    foreach($request->get('packages') as $package)
    {
        $packages[] = $package;
    }
    
    return $packageSelection->writeData($packages);
});

$app->run();
