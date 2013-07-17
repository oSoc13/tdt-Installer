<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the requirements check step of the installer
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class Requirements extends WizardStep
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
        $session->set('requirementsOk', $requirementsOk);
        
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
     * the session.
     * @return boolean
     */
    private function composerInstalled($session)
    {
        $composerCheck = $this->applicationInstalledCheck('composer');
        $filename = implode('', $composerCheck['output']);
        
        if($composerCheck['status'] === 0 && file_exists($filename)) {
            $session->set('composer', $filename);
            
            return true;
        } else {
            $composerCheck = $this->applicationInstalledCheck('composer.phar');
            $filename = implode('', $composerCheck['output']);
            
            if($composerCheck['status'] === 0 && file_exists($filename)) {
                $session->set('composer', $filename);
            }
            var_dump($filename);
            return true;
        }
        
        return false;
    }
    
    /**
     * Checks whether Git is installed.
     * @return boolean
     */
    private function gitInstalled()
    {
        $gitCheck = $this->applicationInstalledCheck('git');
        return $gitCheck['status'] === 0;
    }
    
    /**
     * Checks whether the given application is installed (i.e. can be found in the PATH)
     *
     * @param string The name of the application to be found.
     * @return array An array containing the status and the output.
     */
    private function applicationInstalledCheck($app) {
        $path = exec('echo $PATH');
        $path = explode(':', $path);
        
        $output = '';
        $status = 1;
        $i = 0;
        
        do {
            if($path[$i] !== '.') {
                $command = 'which '. $path[$i] .'/'.$app;
                exec($command, $output, $status);
                var_dump($command);
            }
            $i++;
        } while($i < count($path) && $status !== 0);
        
        $result = array();
        $result['output'] = $output;
        $result['status'] = $status;
        
        return $result;
    }
    
    /**
     * Checks if the correct version of MySQL is installed (5 or higher).
     * @return mixed
     */
    private function mysqlVersionIsCorrect()
    {
        if($this->phpFunctionExists('mysqli_get_client_version')) {
            return substr(mysqli_get_client_version(), 0, 1) >= 5;
        } else {
            return false;
        }
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