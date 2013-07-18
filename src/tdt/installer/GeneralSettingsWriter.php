<?php

namespace tdt\installer;

/**
 * Writes the settings to the Datatank configuration files,
 * changes the names of the Datatank example config files, 
 * changes the name of the Datatank index.php file.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class GeneralSettingsWriter
{ 
    private $configPath = "../app/config/";
    
    public function writeGeneralData($session)
    {
        //$oldGeneralSettingsFile = $this->configPath."general.example.json";
        $generalSettingsFile = $this->configPath."general.json";
        //copy($oldGeneralSettingsFile, $generalSettingsFile);
        
        $generalSettings = array(); //json_decode(file_get_contents($generalSettingsFile), true);
        
        $generalSettings['hostname'] = $session->get('hostname');
        $generalSettings['subdir'] = $session->get('subdir');
        $generalSettings['timezone'] = $session->get('timezone');
        $generalSettings['defaultlanguage'] = $session->get('defaultlanguage');
        $generalSettings['defaultformat'] = $session->get('defaultformat');
        
        $generalSettings['accesslogapache'] = $session->get('accesslogapache');
        $generalSettings['apachelogformat'] = "common";
        
        $generalSettings['cache'] = array();
        $generalSettings['cache']['system'] = $session->get('cachesystem');
        $generalSettings['cache']['host'] = $session->get('cachehost');
        $generalSettings['cache']['port'] = $session->get('cacheport');
        
        $generalSettings['faultinjection'] = array();
        $generalSettings['faultinjection']['enabled'] = true;
        $generalSettings['faultinjection']['period'] = 1000;
        
        $generalSettings['logging'] = array();
        $generalSettings['logging']['enabled'] = $session->get('loggingenabled');
        $generalSettings['logging']['path'] = $session->get('logpath');
        
        $result = file_put_contents($generalSettingsFile, json_encode($generalSettings));
        
        $logmessage = "Writing general configuration settings to {$generalSettingsFile}: " . ($result ? 'OK' : 'Error');
        \tdt\installer\LogWriter::write($logmessage);
        
        return $result;
    }
}