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
        $tempdir = '../tdt/';
        $command = "git clone https://github.com/tdt/start.git {$tempdir}";
        exec($command, $output, $status);
        
        if($status === 0)
        {
            $files = scandir($tempdir);
            foreach($files as $file)
            {
                if($file != '.' && $file != '..')
                {
                    rename($tempdir.$file, "../{$file}");
                }
            }

            $rmdir = rmdir($tempdir);
        }
        
        file_put_contents('settings/gitout.txt', implode("\n", $output) . "\n" . $status . "\n". $rmdir);

        return $status === 0;
    }
}