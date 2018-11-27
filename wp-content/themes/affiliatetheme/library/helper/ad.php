<?php
/**
 * Diverse Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.2
 * @category	helper
 */

if ( ! function_exists('at_referer_ad_register_notice') ) {
    /**
     * at_referer_ad_register_notice function.
     *
     */
    add_action('admin_notices', 'at_referer_ad_register_notice');
    function at_referer_ad_register_notice() {
        if(isset($_GET['page']) && $_GET['page'] == 'at-install-plugins') return;

        $flag = get_option('affiliatetheme_referer_flag');
        $affiliate = get_option('affiliatetheme_referer_affiliate');

        /**
         * Affilinet
         */
        if($flag == 'affilinet' || $affiliate == '5041') {
            if(is_plugin_active('affiliatetheme-affilinet/affiliatetheme-affilinet.php')) return;

            $action = 'install';
            if(is_dir(WP_PLUGIN_DIR . '/affiliatetheme-affilinet')) $action = 'activate';
            ?>
            <div class="announcement announcement-affilinet">
                <div class="announcement-image">
                    <img src="https://affiliatetheme.io/wp-content/uploads/extensions-affilinet.jpg">
                </div>
                <div class="announcement-body">
                    <h2><?php _e('affilinet Plugin installieren', 'affiliatetheme-backend'); ?></h2>
                    <p><?php _e('Installiere direkt das affili.net Plugin um deine ersten Produkte automatisch zu importieren. Eine Anleitung zur Nutzung findest du in unserer <a href="#">Dokumentation</a>.', 'affiliatetheme-backend'); ?></p>
                    <?php
                    if($action == 'install') {
                        $url = admin_url('themes.php?page=at-install-plugins&plugin=affiliatetheme-affilinet&tgmpa-install=install-plugin&tgmpa-nonce=' . wp_create_nonce('tgmpa-install'));
                        ?>
                        <a href="<?php echo $url; ?>" class="btn"><?php _e('Plugin installieren', 'affiliatetheme'); ?></a>
                        <?php
                    } else if($action == 'activate') {
                        $url = admin_url('themes.php?page=at-install-plugins&plugin=affiliatetheme-affilinet&tgmpa-activate=activate-plugin&tgmpa-nonce=' . wp_create_nonce('tgmpa-activate'));
                        ?>
                        <a href="<?php echo $url; ?>" class="btn"><?php _e('Plugin aktivieren', 'affiliatetheme'); ?></a>
                        <?php
                    }
                    ?>
                </div>
                <div class="clear"></div>
            </div>
            <?php
        }

        /**
         * CJ Affiliate
         */
        if($flag == 'cj' || $affiliate == '5042') {
            if(is_plugin_active('affiliatetheme-cj/affiliatetheme-cj.php')) return;

            $action = 'install';
            if(is_dir(WP_PLUGIN_DIR . '/affiliatetheme-cj')) $action = 'activate';
            ?>
            <div class="announcement announcement-cj">
                <div class="announcement-image">
                    <img src="https://affiliatetheme.io/wp-content/uploads/2017/06/extensions-cj-affiliate.jpg">
                </div>
                <div class="announcement-body">
                    <h2><?php _e('CJ Affiliate Plugin installieren', 'affiliatetheme-backend'); ?></h2>
                    <p><?php _e('Installiere direkt das CJ Affiliate Plugin um deine ersten Produkte automatisch zu importieren. Eine Anleitung zur Nutzung findest du in unserer <a href="#">Dokumentation</a>.', 'affiliatetheme-backend'); ?></p>
                    <?php
                    if($action == 'install') {
                        $url = admin_url('themes.php?page=at-install-plugins&plugin=affiliatetheme-cj&tgmpa-install=install-plugin&tgmpa-nonce=' . wp_create_nonce('tgmpa-install'));
                        ?>
                        <a href="<?php echo $url; ?>" class="btn"><?php _e('Plugin installieren', 'affiliatetheme'); ?></a>
                        <?php
                    } else if($action == 'activate') {
                        $url = admin_url('themes.php?page=at-install-plugins&plugin=affiliatetheme-cj&tgmpa-activate=activate-plugin&tgmpa-nonce=' . wp_create_nonce('tgmpa-activate'));
                        ?>
                        <a href="<?php echo $url; ?>" class="btn"><?php _e('Plugin aktivieren', 'affiliatetheme'); ?></a>
                        <?php
                    }
                    ?>
                </div>
                <div class="clear"></div>
            </div>
            <?php
        }
    }
}

if ( ! function_exists('at_referer_ad_register_plugin') ) {
    /**
     * at_referer_ad_register_plugin function.
     *
     */
    add_filter('at_plugin_installer', 'at_referer_ad_register_plugin', 10, 1);
    function at_referer_ad_register_plugin($plugins) {
        $flag = get_option('affiliatetheme_referer_flag');
        $affiliate = get_option('affiliatetheme_referer_affiliate');

        if($flag == 'affilinet' || $affiliate == '5041') {
            $plugins[] = array(
                'name'         => 'AffiliateTheme - affilinet Schnittstelle', // The plugin name.
                'slug'         => 'affiliatetheme-affilinet', // The plugin slug (typically the folder name).
                'source'       => 'http://update.affiliatetheme.io/files/affiliatetheme-affilinet.zip', // The plugin source.
                'required'     => true, // If false, the plugin is only 'recommended' instead of required.
            );
        }

        if($flag == 'cj' || $affiliate == '5042') {
            $plugins[] = array(
                'name'         => 'AffiliateTheme - CJ Affiliate Schnittstelle', // The plugin name.
                'slug'         => 'affiliatetheme-cj', // The plugin slug (typically the folder name).
                'source'       => 'http://update.affiliatetheme.io/files/affiliatetheme-cj.zip', // The plugin source.
                'required'     => true, // If false, the plugin is only 'recommended' instead of required.
            );
        }

        return $plugins;
    }
}