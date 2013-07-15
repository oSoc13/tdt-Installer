<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer for the advanced db configuration
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class DatabaseAdvanced implements WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => true,
            'dbsystemchoices' => $this->getDatabaseChoices(),
            'dbhost' => 'localhost',
        );
    }
    
    public function writeData($data, $session)
    {
        $settingsWriter = new \tdt\installer\SettingsWriter();
        
        $writeData = array();
        
        $writeData['dbsystem'] = 'mysql';
        $writeData['dbhost'] = $data->get('dbhost');
        
        $settingsWriter->writeData($writeData, $session);
    }
    
    
    
    /**
     * Finds the installed PDO extensions in PHP.
     * @return array
     */
    private function getDatabaseChoices()
    {
        $result = array();
        $extensions = get_loaded_extensions();
        
        $values = preg_grep("/pdo.+|PDO.+/", $extensions);
        foreach($values as $value)
        {
            $item = substr($value, 4);
            $result[$item] = $item;
        }
        
        return $result;
    }
}