<?php

namespace tdt\installer;

/**
 * Checks if the requirements for the Datatank are met.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class RequirementsCheck
{
    public function getResult()
    {
        $result = array();
        $result['Directory writable'] = $this->directoryIsWritable();
        $result['MySQL enabled'] = $this->phpModuleEnabled('pdo_mysql');
        $result['Correct MySQL version'] = $this->mysqlVersionIsCorrect();
        $result['curl loaded'] = $this->phpModuleEnabled('curl');
        $result['PHP exec enabled'] = $this->phpFunctionExists('exec');
        $result['mod_rewrite enabled'] = $this->apacheModuleEnabled('mod_rewrite');
        $result['Git installed'] = $this->gitInstalled();
        $result['Composer in PATH'] = $this->composerInstalled();
        
        return $result;
    }
    
    /**
     * Checks whether the given PHP module is enabled.
     * @param string Name of the module
     * @return boolean
     */
    private function phpModuleEnabled($module)
    {
        return extension_loaded($module);
    }
    
    /**
     * Checks whether the given PHP function exists.
     * @param string Name of the function
     * @return boolean
     */
    private function phpFunctionExists($function)
    {
        return function_exists($function);
    }
    
    /**
     * Checks whether the given Apache module is enabled.
     * @param string Name of the module
     * @return boolean
     */
    private function apacheModuleEnabled($module)
    {
        return array_search($module, apache_get_modules()) !== FALSE;
    }
    
    /**
     * Checks whether Composer is installed (i.e. included in the PATH),
     * either as "composer" or as "composer.phar". The result is written to
     * a json config file.
     * It uses the PHP exec function.
     * @return boolean
     */
    private function composerInstalled()
    {
        $output = exec('which composer');
        if(file_exists($line = trim($output))) {
            $this->writeComposerInfo('composer');
            return true;
        } else {
            $output = exec('which composer.phar');
            $result = file_exists($line = trim($output));
            $this->writeComposerInfo('composer.phar');
            return $result;
        }
    }
    
    /**
     * Checks whether Git is installed. It uses the PHP exec function.
     * @return boolean
     */
    private function gitInstalled()
    {
        $output = exec('which git');
        return file_exists($line = trim($output));
    }
    
    /**
     * Checks if the correct version of MySQL is installed (5 or higher).
     * @return mixed
     */
    private function mysqlVersionIsCorrect()
    {
        return substr(mysqli_get_client_version(), 0, 1) >= 5;
    }
    
    /**
     * Checks whether the current directory and the parent directory are writable.
     * @return boolean
     */
    private function directoryIsWritable()
    {
        /*$dir = dirname($_SERVER['PHP_SELF']);
        $dir = $_SERVER['DOCUMENT_ROOT'] . $dir;*/

        return is_writable('.') && is_writable('..');
    }
    
        
    /**
     * Writes the name of the composer executable to the temp.json file.
     */
    private function writeComposerInfo($info)
    {
        $tempFile = 'settings/temp.json';
    
        $tempsettings = json_decode(file_get_contents($tempFile));
        $tempsettings->composer = $info;
        file_put_contents($tempFile, json_encode($tempsettings));
    }
}