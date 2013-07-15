/**
 * Script for the first step of the database configuration
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    setExistingSettingsDisplay(false);
    
    $("#dbnewuser").click(function() {
        setNewSettingsDisplay(true);
        setExistingSettingsDisplay(false);
    });
    
    $("#dbexistinguser").click(function() {
        setNewSettingsDisplay(false);
        setExistingSettingsDisplay(true);
    });
    
}); 

function setNewSettingsDisplay(shouldBeVisible) {
    //$("label[for='newdbsettings']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#newusersettings").css('display', shouldBeVisible ? 'block' : 'none');
}

function setExistingSettingsDisplay(shouldBeVisible) {
    //$("label[for='existingdbsettings']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#existingusersettings").css('display', shouldBeVisible ? 'block' : 'none');
}