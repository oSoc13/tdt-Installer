<?php

namespace tdt\installer;

/**
 * Writes the database settings to the Datatank db configuration file
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class DatabaseSettingsWriter { 

    private $configPath = "../app/config/";
    
    public function writeDatabaseData($session) {
        
        $dbSettingsFile = $this->configPath."db.json";
        
        $dbSettings = array();
        
        if($session->get("dbinstalldefault")) {
            $dbSettings["system"] = "mysql";
            $dbSettings["host"] = "localhost";
            $dbSettings["name"] = "datatank".$session->get("company");
            $dbSettings["password"] = "datatank";
            $dbSettings["user"] = "datatank";
        } else {
            $dbSettings["system"] = $session->get("dbsystem");
            $dbSettings["host"] = $session->get("dbhost");
            
            if($session->get("dbnewuser")) {
                $dbSettings["user"] = $session->get("dbnewusername");
                $dbSettings["password"] = $session->get("dbnewpassword");
            } else {
                $dbSettings["user"] = $session->get("dbuser");
                $dbSettings["password"] = $session->get("dbpassword");
            }
            
            if($session->get("dbnewdb")) {
                $dbSettings["name"] = $session->get("dbnewname");
            } else {
                $dbSettings["name"] = $session->get("dbname");
            }
        }
        
        
        $result = file_put_contents($dbSettingsFile, json_encode($dbSettings));
        
        $logmessage = "Writing database configuration settings to {$dbSettingsFile}: " . ($result ? 'OK' : 'Error');
        \tdt\installer\LogWriter::write($logmessage);
        
        return $result;
    }
}