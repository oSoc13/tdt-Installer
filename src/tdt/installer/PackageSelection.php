<?php

namespace tdt\installer;

/**
 * Writes the composer.json file, based on the packages the user selected.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class PackageSelection
{
    public function writeData($input)
    {
        $composerFile = '../composer.json';
        
        $packageSettings = json_decode(file_get_contents('settings/packages.json'));
        $composerSettings = json_decode(file_get_contents($composerFile), true);
        
        $coreSelected = false;
        
        foreach($input as $packageIndex)
        {
            $packageName = $packageSettings[$packageIndex]->packagename;
            $packageVersion = $packageSettings[$packageIndex]->packageversion;
            
            if($packageName === 'tdt/core') $coreSelected = true;
            
            $composerSettings['require'][$packageName] = $packageVersion;
        }
        
        // The core package is always necessary, so we must make sure it gets installed
        if(!$coreSelected) $composerSettings['require']['tdt/core'] = 'dev-master';
        
        file_put_contents($composerFile, json_encode($composerSettings));
        
        return $input;
    }
}