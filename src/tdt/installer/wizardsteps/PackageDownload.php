<?php

namespace tdt\installer\wizardsteps;

/**
 * Class for the step of the installer where the selected packages are downloaded
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class PackageDownload extends WizardStep
{
    public function getPageContent($session)
    {
        return array(
            'haspreviouspage' => false,
            'hasnextpage' => false,
        );
    }
}