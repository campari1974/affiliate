<?php
/**
 * Affiliate Theme Updater
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	edd
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include(dirname(__FILE__) . '/at-updater-admin.php');
}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://affiliatetheme.io',
		'item_name' => 'AffiliateTheme',
		'theme_slug' => 'affiliatetheme',
		'version' => '',
		'author' => 'endcore Medienagentur',
		'download_id' => '522',
		'renew_url' => '',
        'beta'           => false,
	),

	// Strings
	$strings = array(
		'theme-license' => __( 'Lizenz', 'affiliatetheme-backend' ),
		'enter-key' => __( 'Bitte geben deinen Lizenzschlüssel an.', 'affiliatetheme-backend' ),
		'license-key' => __( 'Lizenzschlüssel', 'affiliatetheme-backend' ),
		'license-action' => __( 'Aktion', 'affiliatetheme-backend' ),
		'deactivate-license' => __( 'Deaktiviere Lizenz', 'affiliatetheme-backend' ),
		'activate-license' => __( 'Aktiviere Lizenz', 'affiliatetheme-backend' ),
		'status-unknown' => __( 'Status der Lizenz unbekannt.', 'affiliatetheme-backend' ),
		'renew' => __( 'Erneuern?', 'affiliatetheme-backend' ),
		'unlimited' => __( 'unlimitiert.', 'affiliatetheme-backend' ),
		'license-key-is-active' => __( 'Lizenzschlüssel ist valide.', 'affiliatetheme-backend' ),
		'expires%s' => __( 'Läuft ab in %s.', 'affiliatetheme-backend' ),
		'expires-never' => __( 'Lifetime Lizenz.', 'affiliatetheme-backend' ),
		'%1$s/%2$-sites' => __( 'Du hast %1$s / %2$s Seiten aktiviert.', 'affiliatetheme-backend' ),
		'license-key-expired-%s' => __( 'Lizenzschlüssel läuft ab am %s.', 'affiliatetheme-backend' ),
		'license-key-expired' => __( 'Lizenzschlüssel ist abgelaufen.', 'affiliatetheme-backend' ),
		'license-keys-do-not-match' => __( 'Lizenzschlüssel stimmt nicht überein.', 'affiliatetheme-backend' ),
		'license-is-inactive' => __( 'Lizenzschlüssel ist inaktiv.', 'affiliatetheme-backend' ),
		'license-key-is-disabled' => __( 'Lizenzschlüssel ist deaktiviert.', 'affiliatetheme-backend' ),
		'site-is-inactive' => __( 'Seite ist inaktiv.', 'affiliatetheme-backend' ),
		'license-status-unknown' => __( 'Status der Lizenz unbekannt.', 'affiliatetheme-backend' ),
		'update-notice' => __( "Updates für dieses Theme überschreiben alle Änderungen in Dateien an diesem Theme. 'Abbrechen' um zu stoppen, 'OK' um Update durchzuführen.", 'affiliatetheme-backend' ),
		'update-available' => __('<strong>%1$s %2$s</strong> ist verfügbar. <a href="%3$s" class="thickbox" title="%4s">Sieh dir an was es neues gibt</a> oder <a href="%5$s"%6$s>aktualisiere jetzt</a>.', 'affiliatetheme-backend' )
	)

);