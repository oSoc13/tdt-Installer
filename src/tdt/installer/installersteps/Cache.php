<?php

namespace tdt\installer\installersteps;

/**
 * Class for the step of the installer where the cache settings are configured.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Cache extends InstallerStep {

    public function getPageContent($session) {
        return array(
            'haspreviouspage' => true,
            'cachingoptions' => $this->getCachingChoices(),
            'cachesystem' => $session->get('cachesystem') !== null ? $session->get('cachesystem') : 'MemCache',
            'cachehost' => $session->get('cachehost') !== null ? $session->get('cachehost') : 'localhost',
            'cacheport' => $session->get('cacheport') !== null ? $session->get('cacheport') : 11211,
        );
    }
    
    public function writeData($data, $session) {
        $settingsWriter = new \tdt\installer\SettingsWriter();
        
        $writeData = array();
        $writeData['cachesystem'] = $data->get('cachesystem');
        $writeData['cachehost'] = $data->get('cachehost');
        $writeData['cacheport'] = $data->get('cacheport');
        
        $settingsWriter->writeData($writeData, $session);
    }
    
    public function validate($data) {
        $cachesystemError = $data->get('cachesystem') !== 'NoCache' && $data->get('cachesystem') !== 'MemCache';
        if($data->get('cachesystem') === 'MemCache') {
            $hostError = $data->get('cachehost') === null;
            $portError = $data->get('cacheport') === null || in_array((int)$data->get('cacheport'), range(0, 65535)) === false;
        } else {
            $hostError = false;
            $portError = false;
        }
        
        if($cachesystemError | $hostError | $portError) {
            return array('cachesystemError' => $cachesystemError, 'hostError' => $hostError, 'portError' => $portError);
        } else {
            return true;
        }
    }
    
    /**
     * Finds the different options for caching. If memcache is installed, it will be
     * added to the options.
     * @return array
     */
    private function getCachingChoices() {
        /*if(class_exists('Memcache'))*/ $result['MemCache'] = 'MemCache';
        $result['NoCache'] = 'NoCache';
        
        return $result;
    }
}
