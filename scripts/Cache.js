/**
 * Script for the cache settings step of the installer.
 * It checks if Memcache is selected and if not, disables
 * the system and host settings on the page.
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
    if( $("#cachesystem").val() == 'MemCache') {
        toggleCacheSettingsReadonly(false);
    } else {
        toggleCacheSettingsReadonly(true);
    }
}

function toggleCacheSettingsReadonly( enabled ) {
    $("#cachehost").prop('readonly', enabled);
    $("#cacheport").prop('readonly', enabled);
}
