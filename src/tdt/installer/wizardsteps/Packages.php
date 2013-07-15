<?php

namespace tdt\installer\wizardsteps;

/**
 * Info
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Packages implements WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => false,
            'packages' => $this->getPackageInfo(),
        );
    }
    
    public function writeData($data, $session)
    {
        $packageSelection = new \tdt\installer\PackageSelection();
    
        $packages = array();
        
        foreach($data->get('packages') as $package)
        {
            $packages[] = $package;
        }
        
        $packageSelection->writeData($packages);
    }
    
    private function getPackageInfo()
    {
        $packageSettings = json_decode(file_get_contents('settings/packages.json'), true);
        
        $result = array();
        
        for($i = 0; $i < count($packageSettings); $i++)
        {
            $packageInfo = array();
            $packageInfo['name'] = $packageSettings[$i]['name'];
            $packageInfo['description'] = $packageSettings[$i]['description'];
            $packageInfo['value'] = $i;
            $result[] = $packageInfo;
        }
        
        return $result;
    }
}