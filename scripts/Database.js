/**
 * Script for the first step of the database configuration
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    $("#next").html('FINISH');
    
    $("#dbinstalldefault").click(function() {
        setRootPasswordDisplay(true);
        $("#next").html('FINISH');
    });
    
    $("#dbinstalladvanced").click(function() {
        setRootPasswordDisplay(false);
        
        $("#next").html('NEXT');
    });
    
}); 

function setRootPasswordDisplay(shouldBeVisible) {
    $("label[for='dbrootpassword']").css('display', shouldBeVisible ? 'inline' : 'none');
    $("#dbrootpassword").css('display', shouldBeVisible ? 'inline' : 'none');
}