/**
 * Script for the second step of the installer (i.e. download tdt/start)
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    $("#output").append("Downloading initial packages...");
    
    $.ajax({
        url: "gitclone",
        cache: false
        }).done(function( result ) {
            
            if( result ) {
                $("#output").empty();
                $("#output").append("All done.");
                
                window.location.href = "?page=2";
            } else {
                $("#output").empty();
                $("#output").append("An error has occured.");
            }
            
        });
    
});