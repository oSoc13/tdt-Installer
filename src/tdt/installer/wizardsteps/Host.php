<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer where the host settings are configured
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Host implements WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => true,
            'hostname' => $session->get('hostname') !== null ? $session->get('hostname') : $this->findDatatankHostname(),
            'subdir' => $session->get('subdir') !== null ? $session->get('subdir') : $this->getSubDirectory(),
            'defaultformat' => $session->get('defaultformat') !== null ? $session->get('defaultformat') : '',
        );
    }
    
    public function writeData($data, $session)
    {
        $settingsWriter = new \tdt\installer\SettingsWriter();
        
        $writeData = array();
        $writeData['hostname'] = $data->get('hostname');
        $writeData['subdir'] = $data->get('subdir');
        $writeData['defaultformat'] = $data->get('defaultformat');
        
        $settingsWriter->writeData($writeData, $session);
    }
    
    public function validate($data)
    {
        $hostnameError = preg_match('/^(https?:\/\/).+\/$/', $data->get('hostname')) === 0;
        $subdirError = preg_match('/^.+\/$/', $data->get('subdir')) === 0;
        $formatError = $data->get('defaultformat') !== 'json' && $data->get('defaultformat') !== 'xml';
        
        if($hostnameError | $subdirError | $formatError) {
            return array('hostnameError' => $hostnameError, 'subdirError' => $subdirError, 'defaultformatError' => $formatError);
        } else {
            return true;
        }
    }
    
    /**
     * Finds the hostname of the machine the installer is running on, including
     * the protocol, e.g. http://example.com/
     * @return string 
     */
    private function findDatatankHostname()
    {
        $httpsOn = isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"]==="on" || $_SERVER["HTTPS"]===1 || $_SERVER["SERVER_PORT"]===443);
        $result = $httpsOn ? "https://" : "http://";
        $result .= gethostname();
        $result .= "/";
        return $result;
    }
    
    /**
     * Finds the subdirectory in which the datatank will is installed. 
     * I.e. the public/ subdir of the directory above the 'install' directory.
     * @return string The directory in which the datatank is installed.
     */
    private function getSubDirectory()
    {
        $dir = explode('/', $_SERVER['REQUEST_URI']);
        
        return $dir[count($dir) - 3] . '/public/';
    }
}