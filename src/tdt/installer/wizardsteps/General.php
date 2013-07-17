<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer where the selected packages are downloaded
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class General extends WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => false,
            'company' => $session->get('company') !== null ? $session->get('company') : '',
            'timezones' => timezone_identifiers_list(),
            'currenttimezone' => $session->get('timezone') !== null ? $session->get('timezone') : date_default_timezone_get(),
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
    
    public function validate($data)
    {
        $companyError = $data->get('company') === null;
        $timezoneError = array_search($data->get('timezone'), timezone_identifiers_list()) === false;
        $languageError = $data->get('defaultlanguage') !== 'en';
        
        if($companyError | $timezoneError | $languageError) {
            return array('companyError' => $companyError, 'timezoneError' => $timezoneError, 'languageError' => $languageError);
        } else {
            return true;
        }
    }
}