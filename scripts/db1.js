/**
 * Script for the first step of the database configuration
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    $("#formsubmit").prop('value', 'Finish');
    
    $("#form_dbinstalldefault").click(function() {
        changeRadioButtons("form_dbinstalldefault", "form_dbinstalladvanced");
        
        setRootPasswordDisplay(true);
        
        $("#formsubmit").prop('value', 'Finish');
    });
    
    $("#form_dbinstalladvanced").click(function() {
        changeRadioButtons("form_dbinstalladvanced", "form_dbinstalldefault");
        
        setRootPasswordDisplay(false);
        
        $("#formsubmit").prop('value', 'Next >');
    });
    
});

function changeRadioButtons(selectedButtonId, unselectedButtonId) {
    $("#" + unselectedButtonId).prop("checked", false);
    $("#" + selectedButtonId).prop("checked", true);
}

function setRootPasswordDisplay(shouldBeVisible) {
    $("label[for='form_dbrootpassword']").css('display', shouldBeVisible ? 'block' : 'none');
    $("#form_dbrootpassword").css('display', shouldBeVisible ? 'block' : 'none');
}