/**
 * Script for the 4th step of the installer (i.e. download packages)
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    $("#title").append("Installing packages");
    $("#output").append("Installing your selected packages...");
    $("#nextButton").css("display", "none");
    
    $.ajax({
        url: "packagedownload",
        dataType: "json",
        success: function (response) {
            toConfig();
        }
    });
    
    outputzone = document.createElement("div");
    outputzone.id = "outputzone";
    
    window.setInterval("updateOutputzone('outputzone');", 500);
    
    $("#output").append(outputzone);
    
});

function updateOutputzone(zone)
{
    $.ajax({
        url: "settings/composeroutput.json",
        dataType: "json",
        success: function (response) {
            if(response.finished === true) {
                toConfig();
            } else {
                $("#"+zone).empty();
                $("#"+zone).append("<pre class='pre-scrollable'>" + response.output + "</pre>");
            }
        }
    });
}

function toConfig()
{
    window.location.href = "config";
}