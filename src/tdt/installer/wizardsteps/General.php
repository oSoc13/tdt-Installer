<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer where the selected packages are downloaded
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class General implements WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => false,
            'timezones' => timezone_identifiers_list(),
            'currenttimezone' => date_default_timezone_get(),
            'languages' => array('en' => 'English'),
        );
    }
    
    public function writeData($data, $session)
    {
        $settingsWriter = new \tdt\installer\SettingsWriter();
        
        $writeData = array();
        $writeData['company'] = $data->get('company');
        $writeData['timezone'] = $data->get('timezone');
        $writeData['defaultlanguage'] = $data->get('defaultlanguage');
        
        $settingsWriter->writeData($writeData, $session);
    }
}