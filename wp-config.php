<?php
/*
|--------------------------------------------------------------------------
| WordPress Configuration
|--------------------------------------------------------------------------
|
| This file has been configured for use with OpenShift.
|
| To learn more about managing WordPress on Openshift, see:
| https://developers.openshift.com/en/php-wordpress.html
|
*/
/*
|--------------------------------------------------------------------------
| OpenShift Recommended Add-on: SendGrid
|--------------------------------------------------------------------------
|
| By default, WordPress uses PHP's mail function to send emails. We
| strongly recommend using SendGrid to ensure messages are delivered to
| both you and your users.
|
| To learn more installing SendGrid, see:
| https://developers.openshift.com/en/marketplace-sendgrid.html#php-wordpress
|
*/
/**
 * Code provided for users following SendGrid instructions linked above.
 */
//define('SENDGRID_USERNAME', getenv('SENDGRID_USERNAME'));
//define('SENDGRID_PASSWORD', getenv('SENDGRID_PASSWORD'));
//define('SENDGRID_SEND_METHOD', 'api');
/*
|--------------------------------------------------------------------------
| WordPress Database Table Prefix
|--------------------------------------------------------------------------
|
| You can have multiple installations in one database if you give each a unique
| prefix. Only numbers, letters, and underscores please!
|
*/
$table_prefix  = 'wp_';
/*
|--------------------------------------------------------------------------
| WordPress Administration Panel
|--------------------------------------------------------------------------
|
| Determine whether the administration panel should be viewed over SSL. We
| prefer to be secure by default.
|
*/
define('FORCE_SSL_ADMIN', false);
/*
|--------------------------------------------------------------------------
| WordPress Debugging Mode - MODIFICATION NOT RECOMMENDED (see below)
|--------------------------------------------------------------------------
| 
| Set OpenShift's APPLICATION_ENV environment variable in order to enable 
| detailed PHP and WordPress error messaging during development.
|
| Set the variable, then restart your app. Using the `rhc` client:
|
|   $ rhc env set APPLICATION_ENV=development -a <app-name>
|   $ rhc app restart -a <app-name>
|
| Set the variable to 'production' and restart your app to deactivate error 
| reporting.
|
| For more information about the APPLICATION_ENV variable, see:
| https://developers.openshift.com/en/php-getting-started.html#development-mode
|
| WARNING: We strongly advise you NOT to run your application in this mode 
|          in production.
|
*/
define('WP_DEBUG', getenv('APPLICATION_ENV') == 'development' ? true : false);
/*
|--------------------------------------------------------------------------
| MySQL Settings - DO NOT MODIFY
|--------------------------------------------------------------------------
|
| WordPress will automatically connect to your OpenShift MySQL database
| by making use of OpenShift environment variables configured below.
|
| For more information on using environment variables on OpenShift, see:
| https://developers.openshift.com/en/managing-environment-variables.html
|
*/
define('DB_NAME', getenv('OPENSHIFT_APP_NAME'));
define('DB_USER', getenv('OPENSHIFT_MYSQL_DB_USERNAME'));
define('DB_PASSWORD', getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));
define('DB_HOST', getenv('OPENSHIFT_MYSQL_DB_HOST') . ':' . getenv('OPENSHIFT_MYSQL_DB_PORT'));
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
/*
|--------------------------------------------------------------------------
| Authentication Unique Keys and Salts - DO NOT MODIFY
|--------------------------------------------------------------------------
|
| Keys and Salts are automatically configured below.
|
/**#@+                                                                                                                                                                                                                                                  
 * Sicherheitsschlüssel                                                                                                                                                                                                                                  
 *                                                                                                                                                                                                                                                      
 * möglichst einmalig genutzte Zeichenkette.                                                                                                                                                                                                            
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}                                                                                                                                                
 * kannst du dir alle Schlüssel generieren lassen.                                                                                                                                                                                                      
 * Du kannst die Schlüssel jederzeit wieder ändern, alle angemeldeten                                                                                                                                                                                   
 * Benutzer müssen sich danach erneut anmelden.                                                                                                                                                                                                         
 *                                                                                                                                                                                                                                                      
 * @since 2.6.0                                                                                                                                                                                                                                         
 */                                                                                                                                                                                                                                                     
define('AUTH_KEY',         'ZIi}+)483szA78=V6sxavS+yIS s7& (N/49gM]^eVQQ;A_>8 0xT&_Izzw+]hE%');                                                                                                                                                       
define('SECURE_AUTH_KEY',  '|YXI#Ib4:q0X<5(hST{9y{qP4Q#EaYtdE`mA-w]-C7.TgVKcWVcwL^EyC5g5k#b)');                                                                                                                                                       
define('LOGGED_IN_KEY',    '0t^^.#K:KA0bIDKN2_8NZ+sl^xa7mnREKMKxs#(KqPC@TuIs(=nxU$>`v!*#J+R ');                                                                                                                                                      
define('NONCE_KEY',        'prD2E|[L9$N#Kl>ny5)@ W@>/]vx=LAk/AeCg<^HJutm@-EX&#X.tcU-4g}9$U_<');                                                                                                                                                       
define('AUTH_SALT',        'Q`/<WFr0<s4DP69=+Orc/jlyoxEDA4pq6.O1EG,A%3T!I3Wr2B^f[bHc$P}p0bf8');                                                                                                                                                       
define('SECURE_AUTH_SALT', 'kgWl}axf)pzgj,F<*9#jLP|7vk-Q,ml#*h8Px9C&-Tt!j>+za9pC!Nu+UB`clo$T');                                                                                                                                                      
define('LOGGED_IN_SALT',   '5pzJ)rwGms7xdy#2?]QP5bsT$v>;t/#Zuz{}u]ua&CA{N9FC4XaW<O^I|v(r(A0!');                                                                                                                                                      
define('NONCE_SALT',       'eIz{FCMe2Y0xFkAg*h8qh]x/@?y/Zp>u >cXl}KQ+R=#nupZpRvEZ9q=1){~c{NZ');                                                                                                                                                      
                                                                                                                                                                                                                                                        
/**#@-*/   
/**

|--------------------------------------------------------------------------
| That's all, stop editing! Happy blogging.
|--------------------------------------------------------------------------
*/

/**                                                                                                                                                                                                                                                     
 * Für Entwickler: Der WordPress-Debug-Modus.                                                                                                                                                                                                           
 *                                                                                                                                                                                                                                                      
 * Plugin- und Theme-Entwicklern wird nachdrücklich empfohlen, WP_DEBUG                                                                                                                                                                                 
 * in ihrer Entwicklungsumgebung zu verwenden.                                                                                                                                                                                                          
 *                                                                                                                                                                                                                                                      
 * Besuche den Codex, um mehr Informationen über andere Konstanten zu finden,                                                                                                                                                                           
 * die zum Debuggen genutzt werden können.                                                                                                                                                                                                              
 *                                                                                                                                                                                                                                                      
 * @link https://codex.wordpress.org/Debugging_in_WordPress                                                                                                                                                                                             
 */                                                                                                                                                                                                                                                     
define('WP_DEBUG', false);  


/** Der absolute Pfad zum WordPress-Verzeichnis. */                                                                                                                                                                                                     
if ( !defined('ABSPATH') )                                                                                                                                                                                                                              
        define('ABSPATH', dirname(__FILE__) . '/');                                                                                                                                                                                                     
                                                                                                                                                                                                                                                        
/** Definiert WordPress-Variablen und fügt Dateien ein.  */                                                                                                                                                                                             
require_once(ABSPATH . 'wp-settings.php'); 




/*
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');
// tell WordPress where the plugins directory really is
if ( !defined('WP_PLUGIN_DIR') && is_link(ABSPATH . '/wp-content/plugins') )
  define('WP_PLUGIN_DIR', realpath(ABSPATH . '/wp-content/plugins'));
// sets up WordPress vars and included files
require_once(ABSPATH . 'wp-settings.php');
*/