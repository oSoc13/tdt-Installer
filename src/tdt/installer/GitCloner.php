<?php

namespace tdt\installer;

/**
 * Clones the git repo into a temporary directory, and then moves all files
 * to the base directory.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class GitCloner {

    public function start() {
        $outputfile = 'settings/gitoutput.json';
        $tempdir = 'tdt/';
        $json = json_decode(file_get_contents('settings/tdt-start.json'));
        $link = $json->link;
        $command = "git clone {$link} {$tempdir}";
        
        $descriptorspec = array(
            0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
            2 => array("pipe", "w")    // stderr is a pipe that the child will write to
        );
        
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
        
        // It seems quite difficult to get the correct exit code of the proc_open process,
        // therefore we need to use a hack to check if the command executed correctly..
        $status = file_exists($tempdir.'composer.json');
        
        if($status === true)
        {
            \tdt\installer\LogWriter::write("Git clone successful.");
            
            $files = scandir($tempdir);
            foreach($files as $file) {
                if($file != '.' && $file != '..') {
                    rename($tempdir.$file, "../{$file}");
                }
            }

            $rmdir = rmdir($tempdir);
            
            \tdt\installer\LogWriter::write("Moving to .. and deleting {$tempdir}: " . ($rmdir ? 'OK' : 'Error'));
            
            $result = true;
        
        } else {
            $tmpfile = 'starttmp.zip';
            $json = json_decode(file_get_contents('settings/tdt-start.json'));
            $link = $json->zip;
            $dir = $json->zipdirname;
            $tmpdir = "../{$dir}/";
            
            $json = json_decode(file_get_contents($outputfile));
            $json->output .= "\nFalling back to ZIP download...";
            \tdt\installer\LogWriter::write("Falling back to zip download.");
            
            file_put_contents($outputfile, json_encode($json));
            $linkOpen = file_put_contents($tmpfile, fopen($link, 'r'));
            
            $zip = new \ZipArchive();
            
            $res = $zip->open('starttmp.zip');
            if ($res === true && $linkOpen != false) {
                $zip->extractTo('..');
                $zip->close();
                
                $files = scandir($tmpdir);
                foreach($files as $file) {
                    
                    if($file != '.' && $file != '..') {
                        rename($tmpdir.$file, "../{$file}");
                    }
                }

                $rmdir = rmdir($tmpdir);
                unlink($tmpfile);
                
                $result = true;
            } else {
                $json->output .= "\nThere was an error, even ZIP download failed...";
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