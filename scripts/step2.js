/**
 * Script for the second step of the installer (i.e. download tdt/start)
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    $("#title").append("Downloading packages");
    $("#output").append("Downloading initial packages...");
    $("#nextButton").css("display", "none");
    
    $.ajax({
        url: "gitclone",
        cache: false
        }).done(function( result ) {
            
            if( result ) {
                $("#output").empty();
                $("#output").append("All done.");
                
                nextStep(3);
            } else {
                $("#output").empty();
                $("#output").append("An error has occured.");
            }
            
        });
    
});