<?php

namespace tdt\installer;

/**
 * Clones the git repo into a temporary directory, and then moves all files
 * to the base directory.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class GitCloner
{
    public function getResult()
    {
        $outputfile = 'settings/gitoutput.json';
        $tempdir = 'tdt/';
        $command = "git clone https://github.com/tdt/start.git {$tempdir}";
        
        $descriptorspec = array(
            0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
            2 => array("pipe", "w")    // stderr is a pipe that the child will write to
        );
        flush();
        
        $process = proc_open($command, $descriptorspec, $pipes, realpath('./'), array());
        
        if (is_resource($process)) {
            \tdt\installer\LogWriter::write("Started Git clone into {$tempdir}");
        
            while ($s = fgets($pipes[1])) {
                $json = json_decode(file_get_contents($outputfile));
                $json->output .= $s;
                file_put_contents($outputfile, json_encode($json));
                flush();
            }
        }
        
        proc_close($process);
        
        // TODO It seems quite difficult to get the exit code of a proc_open process,
        // therefore we are for now using a simpler method to check if the git cloning worked:
        // git cloning was successful if we now have the cloned directory..
        $status = file_exists($tempdir);
        
        if($status === true)
        {
            \tdt\installer\LogWriter::write("Git clone successful.");
            
            $files = scandir($tempdir);
            foreach($files as $file)
            {
                if($file != '.' && $file != '..')
                {
                    rename($tempdir.$file, "../{$file}");
                }
            }

            $rmdir = rmdir($tempdir);
            
            \tdt\installer\LogWriter::write("Moving to .. and deleting {$tempdir}: " . ($rmdir ? 'OK' : 'Error'));
            
            $result = $status;
        
        } else {
            $tmpfile = 'starttmp.zip';
            $tmpdir = '../start-master/';
            $link = 'https://github.com/tdt/start/archive/master.zip';
            
            $json = json_decode(file_get_contents($outputfile));
            $json->output .= "\n\nFalling back to ZIP download...";
            file_put_contents($outputfile, json_encode($json));
            file_put_contents($tmpfile, fopen($link, 'r'));
            \tdt\installer\LogWriter::write("Falling back to zip download.");
            
            $zip = new \ZipArchive();
            
            $res = $zip->open('starttmp.zip');
            if ($res === TRUE) {
                $zip->extractTo('..');
                $zip->close();
                
                $files = scandir($tmpdir);
                foreach($files as $file)
                {
                    
                    if($file != '.' && $file != '..')
                    {
                        rename($tmpdir.$file, "../{$file}");
                    }
                }

                $rmdir = rmdir($tmpdir);
                unlink($tmpfile);
                
                $result = true;
            } else {
                $json->output .= "There was an error, even ZIP download failed...";
                file_put_contents($outputfile, json_encode($json));
                \tdt\installer\LogWriter::write("Zip download failed.");
                
                $result = false;
            }
        }
            
        $json = json_decode(file_get_contents($outputfile));
        $json->finished = true;
        $json->success = $result;
        $json->status = $status;
        file_put_contents($outputfile, json_encode($json));
        
        return $result;
    }
}