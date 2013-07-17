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
        $tmpfile = 'starttmp.zip';
        $tmpdir = '../start-master/';
        $link = 'https://github.com/tdt/start/archive/master.zip';
        
        file_put_contents($tmpfile, fopen($link, 'r'));
        
        $zip = new \ZipArchive();
        
        $res = $zip->open('starttmp.zip');
        if ($res === TRUE) {
            file_put_contents('settings/gitout.txt', $res);
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
        
            return true;
        } else {
            file_put_contents('settings/gitout.txt', $res);
            return false;
        }
    }
}