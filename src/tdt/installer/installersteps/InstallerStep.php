<?php

namespace tdt\installer\installersteps;

/**
 * Abstract base class for steps in the installer.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
abstract class InstallerStep { 

    /**
     * Returns the array with variables to be used on the page.
     * 
     * @param Symfony\Component\HttpFoundation\Session\Session $session The session object, needed to reset the values
     * to saved values if the page gets reloaded.
     * @return array
     */
    function getPageContent($session) {
        return array();
    }
    
    /**
     * Validates the post input data. Returns TRUE if validation is ok,
     * returns an associative array of booleans if there was a problem.
     * Each of these booleans represents a field that did not validate;
     * the array can then be merged with the page variables and sent to
     * the twig/html page.
     * 
     * @param Symfony\Component\HttpFoundation\Request $data The request object containing the input data.
     * @return mixed
     */
    function validate($data) {
        return true;
    }
    
    /**
     * Writes the input data to the session.
     *
     * @param Symfony\Component\HttpFoundation\Request $data The object containing the input data.
     * @param Symfony\Component\HttpFoundation\Session\Session $session The session object to write the data to.
     */
    function writeData($data, $session) {}
}
