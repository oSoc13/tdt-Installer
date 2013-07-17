<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer for the advanced db settings database configuration
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class DatabaseDb extends WizardStep
{
    private $session;
    
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => true,
            'rootpasswordneeded' => $session->get('dbnewuser') === false,
            'dbnewdb' => $session->get('dbnewdb') !== null ? $session->get('dbnewdb') : true,
            'dbrootpassword' => $session->get('dbrootpassword') !== null ? $session->get('dbrootpassword') : true,
            'dbnewname' => $session->get('dbnewname') !== null ? $session->get('dbnewname') : true,
            'dbname' => $session->get('dbname') !== null ? $session->get('dbname') : true,
        );
    }
    
    public function writeData($data, $session)
    {
        $this->session = $session;
        
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
        $choiceError = $data->get('dbdbsetting') !== 'new' && $data->get('dbdbsetting') !== 'existing';
        
        if(!$choiceError && $data->get('dbdbsetting') === 'new') {
            if($this->session->get('dbnewuser') === false) {
                $rootpasswordError = $data->get('dbrootpassword') === null;
            } else {
                $rootpasswordError = false;
            }
                
            $newnameError = $data->get('dbnewname') === null || $data->get('dbnewname') === '';
            $nameError = false;
        } elseif(!$choiceError) {
            $rootpasswordError = false;
            $newnameError = false;
            $nameError = $data->get('dbname') === null || $data->get('dbname') === '';;
        }
        
        if($choiceError | $rootpasswordError | $newnameError | $nameError) {
            return array('choiceError' => $choiceError, 'rootpasswordError' => $rootpasswordError,
                'newnameError' => $newnameError, 'nameError' => $nameError);
        } else {
            return true;
        }
    }
}