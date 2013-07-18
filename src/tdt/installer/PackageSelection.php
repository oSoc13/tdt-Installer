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
        
        for($i = 0; $i < count($packageSettings); $i++) {
            if($packageSettings[$i]->required === true || in_array($i, $input)) {
                $packageName = $packageSettings[$i]->packagename;
                $packageVersion = $packageSettings[$i]->packageversion;
                
                $composerSettings['require'][$packageName] = $packageVersion;
            }
        }
        
        foreach($input as $packageIndex)
        {
            $packageName = $packageSettings[$packageIndex]->packagename;
            $packageVersion = $packageSettings[$packageIndex]->packageversion;
            
            $composerSettings['require'][$packageName] = $packageVersion;
        }
        
        file_put_contents($composerFile, json_encode($composerSettings));
        \tdt\installer\LogWriter::write("Selected packages: " . implode(', ', array_keys($composerSettings['require'])));
        
        return $input;
    }
}