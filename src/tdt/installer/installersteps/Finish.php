<?php

namespace tdt\installer\installersteps;

/**
 * Class for the final step of the Datatank installer
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Finish extends InstallerStep {

    public function getPageContent($session) {
        $returnarray = array(
            'haspreviouspage' => false,
            'sessiondata' => $this->getSessionData($session),
        );
        
        $commitResult = $this->commitSettings($session);
        
        $session->invalidate();
        
        if($commitResult == false) {
            throw new \Exception();
        }
        
        return $returnarray;
    }
    
    /**
     * Writes the session data to the DataTank configuration files,
     * sets up the database and copies the example DataTank files to
     * usable files.
     *
     * @param Symfony\Component\HttpFoundation\Session\Session $session The session object,
     * needed to commit the values.
     */
    private function commitSettings($session) {
        $generalSettingsWriter = new \tdt\installer\GeneralSettingsWriter();
        $dbSettingsWriter = new \tdt\installer\DatabaseSettingsWriter();
        $committer = new \tdt\installer\SettingsCommitter();
        
        $generalresult = $generalSettingsWriter->writeGeneralData($session);
        $dbresult = $dbSettingsWriter->writeDatabaseData($session);
        $commitresult = $committer->commit($session);
        
        return $generalresult & $dbresult & $commitresult;
    }
    
    /**
     * Fetches the data that has been added to the session during the installer.
     * @return array
     */
    private function getSessionData($session) {
        $settings = array(); 
        
        $settings['General']['Hostname'] = $session->get('hostname');
        $settings['General']['Subdirectory'] = $session->get('subdir');
        $settings['General']['Timezone'] = $session->get('timezone');
        $settings['General']['Default language'] = 'English'; //$session->get('defaultlanguage');
        $settings['General']['Default format'] = $session->get('defaultformat');
        
        $settings['Logging']['Apache access log'] = $session->get('accesslogapache');
        $settings['Logging']['Logging enabled'] = $session->get('loggingenabled') ? 'yes' : 'no';
        $settings['Logging']['Logging path'] = $session->get('logpath');
        
        $settings['Cache']['System'] = $session->get('cachesystem');
        if($session->get('cachesystem') == 'MemCache') {
            $settings['Cache']['Host'] = $session->get('cachehost');
            $settings['Cache']['Port'] = $session->get('cacheport');
        }
        
        if($session->get("dbinstalldefault")) {
            $settings['Database']["System"] = "MySQL";
            $settings['Database']["Host"] = "localhost";
            $settings['Database']["Name"] = "datatank".$session->get("company");
            $settings['Database']["User"] = "datatank";
            $settings['Database']["Password"] = "datatank";
        } else {
            $settings['Database']["System"] = "MySQL";
            $settings['Database']["Host"] = $session->get("dbhost");
            
            if($session->get("dbnewuser")) {
                $settings['Database']["User"] = $session->get("dbnewusername");
                $settings['Database']["Password"] = $session->get("dbnewpassword");
            } else {
                $settings['Database']["User"] = $session->get("dbuser");
                $settings['Database']["Password"] = $session->get("dbpassword");
            }
            
            if($session->get("dbnewdb")) {
                $settings['Database']["Name"] = $session->get("dbnewname");
            } else {
                $settings['Database']["Name"] = $session->get("dbname");
            }
        }
        
        return $settings;
    }
}