<?php

namespace tdt\installer\wizardsteps;

/**
 * Abstract base class for steps in the installer wizard.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
abstract class WizardStep
{ 
    function getPageContent($session) {
        return array();
    }
    
    function validate($data) {
        return true;
    }
    
    function writeData($data, $session) {}
}
