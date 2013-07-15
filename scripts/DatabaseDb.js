/**
 * Script for the first step of the database configuration
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    setExistingSettingsDisplay(false);
    
    $("#dbnewdb").click(function() {
        setNewSettingsDisplay(true);
        setExistingSettingsDisplay(false);
    });
    
    $("#dbexistingdb").click(function() {
        setNewSettingsDisplay(false);
        setExistingSettingsDisplay(true);
    });
    
}); 

function setNewSettingsDisplay(shouldBeVisible) {
    //$("label[for='newdbsettings']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#newdbsettings").css('display', shouldBeVisible ? 'block' : 'none');
}

function setExistingSettingsDisplay(shouldBeVisible) {
    //$("label[for='existingdbsettings']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#existingdbsettings").css('display', shouldBeVisible ? 'block' : 'none');
}