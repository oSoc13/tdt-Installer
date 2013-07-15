<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer for the advanced db database configuration
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class DatabaseDb implements WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => true,
            'rootpasswordneeded' => $session->get('dbnewuser') === false,
        );
    }
    
    public function writeData($data, $session)
    {
        $settingsWriter = new \tdt\installer\SettingsWriter();
        
        $writeData = array();
        
        if($data->get('dbdbsetting') === 'new') {
            $writeData['dbnewdb'] = true;
            $writeData['dbnewname'] = $data->get('dbnewname');
            
            if($session->get('dbnewuser') === false)
                $writeData['dbrootpassword'] = $data->get('dbrootpassword');
        } else {
            $writeData['dbnewdb'] = false;
            $writeData['dbname'] = $data->get('dbname');
        }
        
        $settingsWriter->writeData($writeData, $session);
    }
    
    public function validate($data)
    {
        return true;
    }
}