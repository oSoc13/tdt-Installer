<?php

/**
 * The main Installer class, called from the index.php file.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

namespace tdt\installer;

require_once __DIR__.'/RequirementsCheck.php';
require_once __DIR__.'/ConfigurationSetter.php';
require_once __DIR__.'/PackageSelection.php';
require_once __DIR__.'/GitCloner.php';
require_once __DIR__.'/DatabaseSetup.php';

class Installer
{
    /**
     * Checks what step of the installation were at,
     * and calls the corresponding class.
     *
     * @param int The current step in the installation.
     * @return string The text to be displayed when the step is finished.
     */
    public function executeStep($step)
    {
        switch($step)
        {
            case 0:
                $requirements = new RequirementsCheck();
                $result = $requirements->getResult();
                break;
            case 1:
                $gitCloner = new GitCloner();
                $result = $gitCloner->getResult();
                break;
            case 2:
                $packSelection = new PackageSelection();
                $result = $packSelection->getResult();
                break;
            case 3:
                $configsetter = new ConfigurationSetter();
                $result = $configsetter->getResult();
                break;
            case 4:
                $databaseSetup = new DatabaseSetup();
                $result = $databaseSetup->getResult();
                break;
            case 5:
                $result = "Finished.";
                break;
            default:
                $result = "No such step.";
                break;
        }
        
        return $result;
    }
}