<?php

namespace tdt\installer;

/**
 * Checks if the requiremtents for the Datatank are met.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class RequirementsCheck
{
    public function getResult()
    {
	$result = $this->phpModuleEnabled('mysql') ? "MySQL loaded" : "No MySQL";
	$result .= PHP_EOL;
	$result .= $this->phpModuleEnabled('curl') ? "CURL loaded" : "No CURL";
	$result .= PHP_EOL;
	$result .= $this->phpFunctionExists('exec') ? "PHP exec loaded" : "Not loaded";
	$result .= PHP_EOL;
	$result .= $this->apacheModuleEnabled('mod_rewrite') ? "Mod_rewrite loaded" : "Rewrite not loaded";
	
	$result .= PHP_EOL;
	$result .= $this->gitInstalled() ? 'Git installed' : 'No git';
	
	$result .= PHP_EOL;
	$result .= $this->composerInstalled() ? 'Composer installed' : 'No composer';
	
	$result .= PHP_EOL;
	$result .= $this->getMysqlVersion();
	
	$result .= PHP_EOL;
	$result .= $this->directoryIsWritable() ? 'Dir writable' : 'Dir not writable!';
	
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
	return array_search($module, apache_get_modules());
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
     * Returns the version of the MySQL server on the host. Returns NULL if
     * no MySQL version was found.
     * @return mixed
     */
    private function getMysqlVersion()
    {
	$output = exec('mysql -V');
	$match = preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $matches);
	if($match) return $matches[0];
	else return NULL;
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
        $tempsettings = json_decode(file_get_contents('temp.json'));
        $tempsettings->composer = $info;
        file_put_contents('temp.json', json_encode($tempsettings));
    }
}