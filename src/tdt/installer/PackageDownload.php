<?php

namespace tdt\installer;

/**
 * Gets the selected Datatank packages.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class PackageDownload
{
    public function start($session)
    {
        $outputfile = "settings/composeroutput.json";
        $composerFile = '../composer.json';
        
        //$tempsettings = json_decode(file_get_contents('settings/temp.json'));
        
        $command = $session->get('composer').' update -d .. 2>&1';
        $descriptorspec = array(
            0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
            2 => array("pipe", "w")    // stderr is a pipe that the child will write to
        );
        flush();
        
        // To run properly, composer needs a COMPOSER_HOME environment var to be set,
        // but since we're not running it from cli, we need to set this var manually...
        $process = proc_open($command, $descriptorspec, $pipes, realpath('./'), array('COMPOSER_HOME=/home'));
        
        if (is_resource($process)) {
            while ($s = fgets($pipes[1])) {
                $json = json_decode(file_get_contents($outputfile));
                $json->output .= $s;
                file_put_contents($outputfile, json_encode($json));
                flush();
            }
        }
        
        proc_close($process);
        
        $json = json_decode(file_get_contents($outputfile));
        $json->finished = true;
        file_put_contents($outputfile, json_encode($json));
        
        return 0;
    }
}