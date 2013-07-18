/**
 * Script for the first step of the database configuration
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
    console.log('setNewSettingsDisplay');

    //$("#newdbsettings").css('display', shouldBeVisible ? 'block' : 'none');
    if(shouldBeVisible) {
        $("#newdbsettings").show();
    } else {
        $("#newdbsettings").hide();
    }
}

function setExistingSettingsDisplay(shouldBeVisible) {
    console.log('setExistingSettingsDisplay');

    //$("#existingdbsettings").css('display', shouldBeVisible ? 'block' : 'none');
    if(shouldBeVisible) {
        $("#existingdbsettings").show();
    } else {
        $("#existingdbsettings").hide();
    }
}