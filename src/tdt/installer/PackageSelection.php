<?php

namespace tdt\installer;

/**
 * Writes the composer.json file and gets the selected Datatank packages.
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
        
        foreach($input as $package)
        {
            $packageName = $packageSettings[$package]->packagename;
            $packageVersion = $packageSettings[$package]->packageversion;
            
            $composerSettings["require"][$packageName] = $packageVersion;
        }
        
        file_put_contents($composerFile, json_encode($composerSettings));
        
        return $input;
    }
}