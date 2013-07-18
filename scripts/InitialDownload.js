/**
 * Script for the second step of the installer (i.e. downloading tdt/start)
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    $.ajax({
        url: "gitclone",
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
        url: "settings/gitoutput.json",
        dataType: "json",
        success: function (response) {
            if(response.finished === true && response.success === true) {
                window.location.href = "?page=2";
            } else {
                $("#"+zone).empty();
                var pre = document.createElement('pre');
                $(pre).addClass('pre-scrollable');
                $(pre).html(response.output);
                $(pre).appendTo("#"+zone);
                $(pre).scrollTop($(pre).scrollHeight);
            }
        }
    });
}