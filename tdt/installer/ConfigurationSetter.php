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
class ConfigurationSetter
{ 
    public function getResult()
    {
        $configPath = "../app/config/";
        
        $generalSettingsFile = $configPath."general.json";
        $dbSettingsFile = $configPath."db.json";
        
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
        
        copy("../public/index.example.php", "../public/index.php");
        
        return $result ? "Success" : "Failure";
    }
}