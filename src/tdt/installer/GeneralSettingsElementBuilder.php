<?php

namespace tdt\installer;

/**
 * Adds general settings input controls to a FormBuilder
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class GeneralSettingsElementBuilder
{
    const numberOfSteps = 4;

    public function addElements($step, $formBuilder) 
    {
        switch($step)
        {
            case 1:
                $formBuilder->add('company', 'text', array(
                        'label' => 'Company',
                    ));
                $formBuilder->add('timezone', 'timezone', array(
                        'expanded' => false,
                        'label' => 'Timezone',
                        'data' => date_default_timezone_get(),
                    ));
                $formBuilder->add('defaultlanguage', 'choice', array(
                        'choices' => array('en' => 'English'),
                        'expanded' => false,
                        'label' => 'Language',
                    ));
                break;
            case 2:
                $formBuilder->add('hostname', 'text', array(
                        'label' => 'Hostname',
                        'data' => $this->findDatatankHostname(),
                    ));
                $formBuilder->add('subdir', 'text', array(
                        'label' => 'Subdirectory',
                        'data' => $this->getSubDirectory(),
                    ));
                $formBuilder->add('defaultformat', 'choice', array(
                        'label' => 'Default format',
                        'choices' => $this->getDefaultFormatOptions(),
                    ));
                break;
            case 3:
                $formBuilder->add('accesslogapache', 'text', array(
                        'label' => 'Apache access log',
                        'data' => '/var/log/apache2/access.log',
                    ));
                $formBuilder->add('logenabled', 'checkbox', array(
                        'label' => 'Logging',
                        'required' => false,
                    ));
                $formBuilder->add('logpath', 'text', array(
                        'label' => 'Log path',
                        'data' => '/tmp',
                    ));
                break;
            case 4:
                $formBuilder->add('cachesystem', 'choice', array(
                        'choices' => $this->getCachingChoices(),
                        'data' => 'NoCache',
                        'multiple' => false,
                    ));
                $formBuilder->add('cachehost', 'text', array(
                        'label' => 'Host',
                        'data' => 'localhost',
                    ));
                $formBuilder->add('cacheport', 'text', array(
                        'label' => 'Port',
                        'data' => '11211',
                    ));
                break;
            default:
                break;
        }
        
        return $formBuilder;
    }
    
    /**
     * Finds the hostname of the machine the installer is running on, including
     * the protocol, e.g. http://example.com/
     * @return string 
     */
    private function findDatatankHostname()
    {
        $httpsOn = isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"]==="on" || $_SERVER["HTTPS"]===1 || $_SERVER["SERVER_PORT"]===443);
        $result = $httpsOn ? "https://" : "http://";
        $result .= gethostname();
        $result .= "/";
        return $result;
    }
    
    /**
     * Finds the subdirectory in which the datatank will is installed. 
     * I.e. the public/ subdir of the directory above the 'install' directory.
     * @return string The directory in which the datatank is installed.
     */
    private function getSubDirectory()
    {
        $pathInfo = pathinfo(__DIR__);
        $path = explode('/', $pathInfo['dirname']);
        
        $i = count($path) - 1;
        while($path[$i] !== "install") {
            $i--;
        }
        
        return $path[$i - 1] . "/public/";
    }
    
    /**
     * Finds the Datatank's format options and returns them as HTML option elements.
     * @return array 
     */
    private function getDefaultFormatOptions()
    {
        $result['json'] = "JSON";
        $result['xml'] = "XML";
        
        return $result;
    }
    
    /**
     * Finds the different options for caching. If memcache is installed, it will be
     * added to the options.
     * @return array
     */
    private function getCachingChoices()
    {
        $result['NoCache'] = 'NoCache';
        if(class_exists('Memcache')) $result['MemCache'] = 'MemCache';
        
        return $result;
    }
}