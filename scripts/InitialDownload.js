/**
 * Script for the second step of the installer (i.e. downloading tdt/start)
 * The gitclone url is called via AJAX; the output of the Git command is updated
 * every 500ms by checking the settings/gitoutput.json file.
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

/**
 * Updates the output of the Git command.
 * When finished, it will redirect to the next installer step.
 */
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