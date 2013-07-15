<?php

namespace tdt\installer\wizardsteps;

/**
 * Interface for steps in the installer wizard.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
interface WizardStep
{ 
    function getPageContent($session);
}
