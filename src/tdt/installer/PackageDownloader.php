<?php

namespace tdt\installer;

/**
 * Gets the selected Datatank packages by running a composer update.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class PackageDownloader
{
    public function start($session)
    {
        $outputfile = "settings/composeroutput.json";
        $composerFile = '../composer.json';
        
        // When checking the requirements, we saved the composer location to the session!
        $command = $session->get('composer').' update -d .. 2>&1';
        
        $descriptorspec = array(
            0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
            2 => array("pipe", "w")    // stderr is a pipe that the child will write to
        );
        
        // To run properly, composer needs a COMPOSER_HOME environment var to be set,
        // but since we're not running it from cli, we need to set this var manually...
        $process = proc_open($command, $descriptorspec, $pipes, realpath('./'), array('COMPOSER_HOME=/home'));
        
        if (is_resource($process)) {
            \tdt\installer\LogWriter::write("Started composer update.");
            
            while ($s = fgets($pipes[1])) {
                $json = json_decode(file_get_contents($outputfile));
                $json->output .= $s;
                file_put_contents($outputfile, json_encode($json));
                flush();
            }
            
            $status = proc_get_status($process);
            $status = $status['exitcode'];
        }
        
        proc_close($process);
        
        $result = $status === 0;
        
        $json = json_decode(file_get_contents($outputfile));
        $json->finished = true;
        $json->success = $result;
        \tdt\installer\LogWriter::write("Composer update: " . ($result ? 'OK' : 'Error (check settings/composeroutput.json for error information)'));
        file_put_contents($outputfile, json_encode($json));
        
        return $result;
    }
}