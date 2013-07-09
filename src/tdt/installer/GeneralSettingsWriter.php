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
        $generalSettings['logging']['enabled'] = $session->get('logenabled');
        $generalSettings['logging']['path'] = $session->get('logpath');
        
        $result = file_put_contents($generalSettingsFile, json_encode($generalSettings));
        
        return $result ? "Success" : "Failure";
    }
}


        /*$dbSettingsFile = $configPath."db.json";
        
        $oldCoresFile = $configPath."cores.example.json";
        $newCoresFile = $configPath."cores.json";
        copy($oldCoresFile, $newCoresFile);
        
        $oldAuthFile = $configPath."auth.example.json";
        $newAuthFile = $configPath."auth.json";
        copy($oldAuthFile, $newAuthFile);
        
        $generalSettings = array();
        $generalSettings['hostname'] = "http://localhost/";
        $generalSettings['subdir'] = "installer/public/";
        $generalSettings['timezone'] = "Europe/Brussels";
        $generalSettings['defaultlanguage'] = "en";
        $generalSettings['defaultformat'] = "json";
        
        $generalSettings['accesslogapache'] = "/var/log/apache2/access.log";
        $generalSettings['apachelogformat'] = "common";
        
        $generalSettings['cache'] = array();
        $generalSettings['cache']['system'] = "NoCache";
        $generalSettings['cache']['host'] = "localhost";
        $generalSettings['cache']['port'] = 11211;
        
        $generalSettings['faultinjection'] = array();
        $generalSettings['faultinjection']['enabled'] = true;
        $generalSettings['faultinjection']['period'] = 1000;
        
        $generalSettings['logging'] = array();
        $generalSettings['logging']['enabled'] = true;
        $generalSettings['logging']['path'] = "/tmp";
        
        $result = file_put_contents($generalSettingsFile, json_encode($generalSettings));
        
        $dbSettings = array();
        $dbSettings['system'] = "mysql";
        $dbSettings['host'] = "localhost";
        $dbSettings['name'] = "tdttest";
        $dbSettings['user'] = "tdttest";
        $dbSettings['password'] = "tdttest";
        
        $result = $result && file_put_contents($dbSettingsFile, json_encode($dbSettings));
        
        copy("../public/index.example.php", "../public/index.php");*/