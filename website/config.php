<?php
// Database connection parameters

define('SERVER', '');
define('USER', '');
define('PASS', '');
define('DB', '');

/**
 * Password required to create the root user account 
 */
define('ROOT_PASS', '');

/**
 * Mapping from language codes in a user's HTTP_ACCEPT_LANGUAGE header to 
 * locale names on the server. Locales must be those for which there is a 
 * translation file under the "locale" directory. If may be necessary to have a
 * ".utf8" suffix in the name even if there is none in the actual directory 
 * name.
 *  
 */
$LANG_TO_LOCALE = array(
    'en-US'    => 'en_US.utf8',
    'en'       => 'en_US.utf8',
    'fi-FI'    => 'fi_FI.utf8',
    'fi'       => 'fi_FI.utf8');

/**
 * The locale to use if no user preference can be detected. Should be one of 
 * the names defined in $LANG_TO_LOCALE.
 * 
 */
define('DEFAULT_LOCALE', 'en_US.utf8');

// The settings below this do not normally require modification

/**
 * ID of the sole row in the settings table
 * 
 */
define('SETTINGS_ROW', 0);

// IDs of user types in the user type table

define('USER_TYPE_ID_USER', 0);
define('USER_TYPE_ID_ADMIN', 1);
define('USER_TYPE_ID_ROOT', 2);