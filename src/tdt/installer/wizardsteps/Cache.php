<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer where the selected packages are downloaded
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Cache implements WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => true,
            'cachingoptions' => $this->getCachingChoices(),
            'cachehost' => 'localhost',
            'cacheport' => 11211,
        );
    }
    
    public function writeData($data, $session)
    {
        $settingsWriter = new \tdt\installer\SettingsWriter();
        
        $writeData = array();
        $writeData['cachesystem'] = $data->get('cachesystem');
        $writeData['cachehost'] = $data->get('cachehost');
        $writeData['cacheport'] = $data->get('cacheport');
        
        $settingsWriter->writeData($writeData, $session);
    }
    
    /**
     * Finds the different options for caching. If memcache is installed, it will be
     * added to the options.
     * @return array
     */
    private function getCachingChoices()
    {
        $result['NoCache'] = 'NoCache';
        if(class_exists('Memcache')) $result['MemCache'] = 'MemCache';
        
        return $result;
    }
}