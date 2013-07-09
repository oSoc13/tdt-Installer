/**
 * Script for the third step of the installer (i.e. package selection)
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    $("#title").append("Select packages");
    $("#output").append("Loading...");
    
    $("#contentForm").attr("action", "packageselection");
    $("#contentForm").attr("method", "post");
    
    $("#nextButton").removeAttr("disabled");
    
    $("#nextButton").click(function() {
        $("#contentForm").submit();
        nextStep(4);
    });
    
    $.ajax({
        url: "settings/packages.json",
        dataType: "json",
        success: function (response) {
            
            $("#output").empty();
            
            $.each(response, function (key, tdtPackage) {
                var result = "<input type='checkbox' name='packages[]' value='" + key + "'";
                if(tdtPackage.required) result += " checked readonly";
                result += ">";
                result += tdtPackage.name + "</input>";
                result += "<p>" + tdtPackage.description + "</p>";
                
                $("#output").append(result);
            });
        }
    });
});