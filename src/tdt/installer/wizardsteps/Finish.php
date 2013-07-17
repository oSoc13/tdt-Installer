<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the final step of the Datatank installer
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Finish implements WizardStep
{
    public function getPageContent($session)
    {
        $returnarray = array(
            'haspreviouspage' => false,
            'sessiondata' => $this->getSessionData($session),
        );
        
        $this->commitSettings($session);
        
        $session->invalidate();
        
        return $returnarray;
    }
    
    private function commitSettings($session) {
        $generalSettingsWriter = new \tdt\installer\GeneralSettingsWriter();
        $dbSettingsWriter = new \tdt\installer\DatabaseSettingsWriter();
        $committer = new \tdt\installer\SettingsCommitter();
        
        $generalSettingsWriter->writeGeneralData($session);
        $dbSettingsWriter->writeDatabaseData($session);
        $committer->commit($session);
    }
    
    /**
     * Fetches the data that has been added to the session during the installer.
     * @return array
     */
    private function getSessionData($session) {
        $settings = array(); 
        
        $settings['hostname'] = $session->get('hostname');
        $settings['subdir'] = $session->get('subdir');
        $settings['timezone'] = $session->get('timezone');
        $settings['defaultlanguage'] = $session->get('defaultlanguage');
        $settings['defaultformat'] = $session->get('defaultformat');
        
        $settings['accesslogapache'] = $session->get('accesslogapache');
        $settings['loggingenabled'] = $session->get('loggingenabled');
        $settings['loggingpath'] = $session->get('logpath');
        
        $settings['cachesystem'] = $session->get('cachesystem');
        $settings['cachehost'] = $session->get('cachehost');
        $settings['cacheport'] = $session->get('cacheport');
        
        if($session->get("dbinstalldefault")) {
            $settings["system"] = "mysql";
            $settings["host"] = "localhost";
            $settings["name"] = "datatank".$session->get("company");
            $settings["password"] = "datatank";
            $settings["user"] = "datatank";
        } else {
            $settings["system"] = $session->get("dbsystem");
            $settings["host"] = $session->get("dbhost");
            
            if($session->get("dbnewuser")) {
                $settings["user"] = $session->get("dbnewusername");
                $settings["password"] = $session->get("dbnewpassword");
            } else {
                $settings["user"] = $session->get("dbuser");
                $settings["password"] = $session->get("dbpassword");
            }
            
            if($session->get("dbnewdb")) {
                $settings["name"] = $session->get("dbnewname");
            } else {
                $settings["name"] = $session->get("dbname");
            }
        }
        
        return $settings;
    }
}