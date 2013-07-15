<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer where the logging is configured
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Logging implements WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => true,
        );
    }
    
    public function writeData($data, $session)
    {
        $settingsWriter = new \tdt\installer\SettingsWriter();
        
        $writeData = array();
        $writeData['accesslogapache'] = $data->get('accesslogapache');
        $writeData['loggingenabled'] = $data->get('loggingenabled') == 'on';
        $writeData['logpath'] = $data->get('logpath');
        
        $settingsWriter->writeData($writeData, $session);
    }
}