<?php

namespace tdt\installer\installersteps;

/**
 * Class for the step of the installer where the logging is configured
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Logging extends InstallerStep {

    public function getPageContent($session) {
        return array(
            'haspreviouspage' => true,
            'accesslogapache' => $session->get('accesslogapache') !== null ? $session->get('accesslogapache') : '/var/log/apache2/access.log',
            'loggingenabled' => $session->get('loggingenabled') === false ? false : true,
            'logpath' => $session->get('logpath') !== null ? $session->get('logpath') : '/tmp',
        );
    }
    
    public function writeData($data, $session) {
        $settingsWriter = new \tdt\installer\SettingsWriter();
        
        $writeData = array();
        $writeData['accesslogapache'] = $data->get('accesslogapache');
        $writeData['loggingenabled'] = $data->get('loggingenabled') == 'on';
        $writeData['logpath'] = $data->get('logpath');
        
        $settingsWriter->writeData($writeData, $session);
    }
    
    public function validate($data) {
        $apachelogError = preg_match('/^.*access.log$/', $data->get('accesslogapache')) === 0;
        $logpathError = $data->get('logpath') === null;
        
        if($apachelogError | $logpathError) {
            return array('apachelogError' => $apachelogError, 'logpathError' => $logpathError);
        } else {
            return true;
        }
    }
}