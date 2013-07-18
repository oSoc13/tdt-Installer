/**
 * Script to create the start button of the installer. 
 * JavaScript is required for the installer, so we have to check if the
 * user has it enabled.
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    if($("#startbutton").length > 0) {
        $("#startbutton").empty();
        $("#startbutton").append('<a class="btn" id="start" href="?page=1">START</a>');
    }
});
