<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the requirements check step of the installer
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Requirements implements WizardStep
{
    public function getPageContent($session)
    {
        $result = array();
        $result['writable'] = $this->directoryIsWritable();
        $result['mysqlenabled'] = $this->phpModuleEnabled('pdo_mysql');
        $result['mysqlversionok'] = $this->mysqlVersionIsCorrect();
        $result['curl'] = $this->phpModuleEnabled('curl');
        $result['exec'] = $this->phpFunctionExists('exec');
        $result['modrewrite'] = $this->apacheModuleEnabled('mod_rewrite');
        $result['git'] = $this->gitInstalled();
        $result['composer'] = $this->composerInstalled($session);
        
        $requirementsOk = true;
        
        foreach($result as $check)
        {
            $requirementsOk = $requirementsOk & $check;
        }
        
        $result['requirementsok'] = $requirementsOk;
        
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
    private function composerInstalled($session)
    {
        $output = exec('which composer');
        if(file_exists($line = trim($output))) {
            $session->set('composer', 'composer');
            return true;
        } else {
            $output = exec('which composer.phar');
            $result = file_exists($line = trim($output));
            $session->set('composer', 'composer.phar');
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
        return is_writable('.') && is_writable('..');
    }
}