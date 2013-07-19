<?php

namespace tdt\installer;

/**
 * Writes info to the log file.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class LogWriter
{ 
    const logfile = 'settings/installer.log';
    
    public static function write($data) {
        //$date = new \DateTime();
        $data = /*$date->format('Y-m-d H:i:s') . ' ' .*/ $data . "\n";
        file_put_contents(self::logfile, $data, FILE_APPEND);
    }
}