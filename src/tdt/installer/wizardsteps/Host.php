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
            'hostname' => $this->findDatatankHostname(),
            'subdir' => $this->getSubDirectory(),
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
        $pathInfo = pathinfo(__DIR__);
        $path = explode('/', $pathInfo['dirname']);
        
        $i = count($path) - 1;
        while($path[$i] !== "install") {
            $i--;
        }
        
        return $path[$i - 1] . "/public/";
    }
}