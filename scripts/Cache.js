/**
 * Script for the cache settings step of the installer
 * 
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */

$( document ).ready(function() {
    
    checkCacheSystem();
    
    $("#cachesystem").change(function () {
        checkCacheSystem();
    });
    
});

function checkCacheSystem() {
    if( $("#cachesystem").attr('value') == 'NoCache') {
        toggleCacheSettingsEnabled(false);
    } else {
        toggleCacheSettingsEnabled(true);
    }
}

function toggleCacheSettingsEnabled( enabled ) {
    $("#cachehost").prop('readonly', enabled);
    $("#cacheport").prop('readonly', enabled);
}