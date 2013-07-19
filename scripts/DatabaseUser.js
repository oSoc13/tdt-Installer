/**
 * Script for the advanced user configuration
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    setNewSettingsDisplay($("#dbnewuser").is(':checked'));
    setExistingSettingsDisplay($("#dbexistinguser").is(':checked'));
    
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
    if(shouldBeVisible) {
        $("#newusersettings").show();
    } else {
        $("#newusersettings").hide();
    }
}

function setExistingSettingsDisplay(shouldBeVisible) {
    if(shouldBeVisible) {
        $("#existingusersettings").show();
    } else {
        $("#existingusersettings").hide();
    }
}