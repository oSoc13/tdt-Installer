<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer where the user makes the selection
 * between default or advanced database installation
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Database implements WizardStep
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
        
        if($data->get('dbinstall') === 'default') {
            $writeData['dbinstalldefault'] = true;
            $writeData['dbrootpassword'] = $data->get('dbrootpassword');
        } else {
            $writeData['dbinstalldefault'] = false;
        }
        
        $settingsWriter->writeData($writeData, $session);
    }
}