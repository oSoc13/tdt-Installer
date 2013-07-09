/**
 * Script to redirect to next step of the installer
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

function nextStep( step ) {
    window.location.href = "?step=" + step;
}