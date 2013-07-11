/**
 * Script for the first step of the installer (i.e. requirements check)
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    $("#title").append("Requirements check");
    $("#output").append("Checking...");
    
    $.ajax({
        url: "requirements",
        cache: false
        }).done(function( data ) {
            
            var allRequirementsMet = true;
            
            $("#output").empty();
            
            $.each( data, function(key, value) {
                var output = "<p>";
                output += key;
                output += ": ";
                output += value ? "OK" : "Not OK";
                output += "</p>";
                
                allRequirementsMet = allRequirementsMet & value;
                
                $("#output").append( output );
            });
            
            if(allRequirementsMet) {
                $("#nextButton").removeAttr("disabled");
            }
            
        });
    
});