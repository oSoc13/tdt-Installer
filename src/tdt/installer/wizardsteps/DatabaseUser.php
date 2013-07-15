<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer for the advanced db user configuration
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class DatabaseUser implements WizardStep
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
        
        if($data->get('dbusersetting') === 'new') {
            $writeData['dbnewuser'] = true;
            $writeData['dbrootpassword'] = $data->get('dbrootpassword');
            $writeData['dbnewpassword'] = $data->get('dbnewpassword');
            $writeData['dbnewusername'] = $data->get('dbnewusername');
        } else {
            $writeData['dbnewuser'] = false;
            $writeData['dbuser'] = $data->get('dbuser');
            $writeData['dbpassword'] = $data->get('dbpassword');
        }
        
        $settingsWriter->writeData($writeData, $session);
    }
}