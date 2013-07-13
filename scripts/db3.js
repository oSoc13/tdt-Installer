/**
 * Script for the third step of the database configuration
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    setExistingUserInfoDisplay(false);
    
    $("#form_dbnewuser").click(function() {
        changeRadioButtons("form_dbnewuser", "form_dbexistinguser");
        
        setNewUserInfoDisplay(true);
        setExistingUserInfoDisplay(false);
    });
    
    $("#form_dbexistinguser").click(function() {
        changeRadioButtons("form_dbexistinguser", "form_dbnewuser");
        
        setNewUserInfoDisplay(false);
        setExistingUserInfoDisplay(true);
    });
    
});

function changeRadioButtons(selectedButtonId, unselectedButtonId) {
    $("#" + unselectedButtonId).prop("checked", false);
    $("#" + selectedButtonId).prop("checked", true);
}

function setNewUserInfoDisplay(shouldBeVisible) {
    $("label[for='form_dbrootpassword']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbrootpassword").css('display', shouldBeVisible ? 'block' : 'none');
    
    $("label[for='form_dbnewusername']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbnewusername").css('display', shouldBeVisible ? 'block' : 'none');
    
    $("label[for='form_dbnewpassword']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbnewpassword").css('display', shouldBeVisible ? 'block' : 'none');
}

function setExistingUserInfoDisplay(shouldBeVisible) {
    $("label[for='form_dbuser']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbuser").css('display', shouldBeVisible ? 'block' : 'none');
    
    $("label[for='form_dbpassword']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbpassword").css('display', shouldBeVisible ? 'block' : 'none');
}