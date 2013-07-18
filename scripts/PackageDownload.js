/**
 * Script for the 4th step of the installer (i.e. downloading selected packages using composer)
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    $.ajax({
        url: "packagedownload",
        dataType: "json",
        success: function (response) {
            //window.location.href = "?page=4";
        }
    });
    
    outputzone = document.createElement("div");
    outputzone.id = "output";
    
    window.setInterval("updateOutputzone('output');", 500);
    
    $("#output").append(outputzone);
    
});

function updateOutputzone(zone)
{
    $.ajax({
        url: "settings/composeroutput.json",
        dataType: "json",
        success: function (response) {
            if(response.finished === true) {
                window.location.href = "?page=4";
            } else {
                $("#"+zone).empty();
                var pre = document.createElement('pre');
                $(pre).addClass('pre-scrollable');
                $(pre).html(response.output);
                $(pre).appendTo("#"+zone);
                
                // ugly hack to make the div scroll down..
                $(pre).scrollTop(99999);//$(pre).scrollHeight);
            }
        }
    });
}