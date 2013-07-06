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
        $output = exec($command);

        $files = scandir($tempdir);
        foreach($files as $file)
        {
            if($file != '.' && $file != '..')
            {
                rename($tempdir.$file, "../{$file}");
            }
        }

        rmdir($tempdir);

        return "Ran a git command!: " . PHP_EOL . $output;
    }
}