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
    public function getResult()
    {
        $composerFile = '../composer.json';
        
        $tempsettings = json_decode(file_get_contents('temp.json'));
        $composerSettings = json_decode(file_get_contents($composerFile), true);
        
        //$composerSettings["require"]["tdt/input"] = "dev-master";
        //$composerSettings["require"]["oSoc13/tdt-core"] = "dev-master";
        
        //unset($composerSettings["require"]["tdt/core"]);
        //unset($composerSettings["suggest"]["tdt/core"]);
        
        file_put_contents($composerFile, json_encode($composerSettings));
        
        // To run properly, composer needs a COMPOSER_HOME environment var to be set,
        // but since we're not running it from cli, we need to set this var manually...
        putenv('COMPOSER_HOME=/home');
        
        $command = $tempsettings->composer.' update -d .. 2>&1';
        exec($command, $out);
        
        return $out;
    }
}