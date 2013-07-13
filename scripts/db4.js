/**
 * Script for the 4th step of the database configuration
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    setExistingDatabaseDisplay(false);
    
    $("#form_dbnewdb").click(function() {
        changeRadioButtons("form_dbnewdb", "form_dbexistingdb");
        
        setNewDatabaseDisplay(true);
        setExistingDatabaseDisplay(false);
    });
    
    $("#form_dbexistingdb").click(function() {
        changeRadioButtons("form_dbexistingdb", "form_dbnewdb");
        
        setNewDatabaseDisplay(false);
        setExistingDatabaseDisplay(true);
    });
    
});

function changeRadioButtons(selectedButtonId, unselectedButtonId) {
    $("#" + unselectedButtonId).prop("checked", false);
    $("#" + selectedButtonId).prop("checked", true);
}

function setNewDatabaseDisplay(shouldBeVisible) {
    $("label[for='form_dbrootpassword']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbrootpassword").css('display', shouldBeVisible ? 'block' : 'none');
    
    $("label[for='form_dbnewname']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbnewname").css('display', shouldBeVisible ? 'block' : 'none');
}

function setExistingDatabaseDisplay(shouldBeVisible) {
    $("label[for='form_dbname']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbname").css('display', shouldBeVisible ? 'block' : 'none');
}