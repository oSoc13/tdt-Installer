<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer for the advanced db user configuration
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class DatabaseUser extends WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => true,
            'dbnewuser' => $session->get('dbnewuser') !== null ? $session->get('dbnewuser') : true,
            'dbrootpassword' => $session->get('dbrootpassword') !== null ? $session->get('dbrootpassword') : '',
            'dbnewpassword' => $session->get('dbnewpassword') !== null ? $session->get('dbnewpassword') : '',
            'dbnewusername' => $session->get('dbnewusername') !== null ? $session->get('dbnewusername') : '',
            'dbuser' => $session->get('dbuser') !== null ? $session->get('dbuser') : '',
            'dbpassword' => $session->get('dbpassword') !== null ? $session->get('dbpassword') : '',
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
    
    public function validate($data)
    {
        $choiceError = $data->get('dbusersetting') !== 'new' && $data->get('dbusersetting') !== 'existing';
        
        if(!$choiceError && $data->get('dbusersetting') === 'new') {
            $rootpasswordError = $data->get('dbrootpassword') === null;
            $newpasswordError = $data->get('dbnewpassword') === null;
            $passwordConfirmError = $data->get('dbnewpassword') !== $data->get('dbconfirmpassword');
            $newusernameError = $data->get('dbnewusername') === null || $data->get('dbnewusername') === '';
            $passwordError = false;
            $userError = false;
        } elseif(!$choiceError) {
            $rootpasswordError = false;
            $newpasswordError = false;
            $passwordConfirmError = false;
            $newusernameError = false;
            $passwordError = $data->get('dbpassword') === null;
            $userError = $data->get('dbuser') === null || $data->get('dbuser') === '';
        }
        
        if($choiceError | $rootpasswordError | $newpasswordError | $passwordConfirmError 
            | $newusernameError | $passwordError | $userError) {
            
            return array('choiceError' => $choiceError, 'rootpasswordError' => $rootpasswordError,
                'newpasswordError' => $newpasswordError, 'passwordConfirmError' => $passwordConfirmError,
                'newusernameError' => $newusernameError, 'passwordError' => $passwordError, 'userError' => $userError);
        } else {
            return true;
        }
    }
}