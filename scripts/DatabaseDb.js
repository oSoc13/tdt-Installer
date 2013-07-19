/**
 * Script for the advanced database configuration.
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    setNewSettingsDisplay($("#dbnewdb").is(':checked'));
    setExistingSettingsDisplay($("#dbexistingdb").is(':checked'));
    
    $("#dbnewdb").click(function(e) {
        setNewSettingsDisplay(true);
        setExistingSettingsDisplay(false);
    });
    
    $("#dbexistingdb").click(function(e) {
        setNewSettingsDisplay(false);
        setExistingSettingsDisplay(true);
    });
    
}); 

function setNewSettingsDisplay(shouldBeVisible) {
    if(shouldBeVisible) {
        $("#newdbsettings").show();
    } else {
        $("#newdbsettings").hide();
    }
}

function setExistingSettingsDisplay(shouldBeVisible) {
    if(shouldBeVisible) {
        $("#existingdbsettings").show();
    } else {
        $("#existingdbsettings").hide();
    }
}