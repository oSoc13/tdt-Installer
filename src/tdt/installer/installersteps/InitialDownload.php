<?php

namespace tdt\installer\installersteps;

/**
 * Class for the step of the installer where the tdt/start packages are downloaded
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class InitialDownload extends InstallerStep {

    public function getPageContent($session) {
        return array(
            'haspreviouspage' => false,
            'hasnextpage' => false,
        );
    }
}