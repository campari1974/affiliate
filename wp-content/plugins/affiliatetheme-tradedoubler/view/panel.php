<?php date_default_timezone_set( 'Europe/Berlin' ); ?>
<div class="at-ajax-loader">
    <div class="inner">
        <p></p>

        <div class="progress">
            <div class="progress-bar" style="width:0%;" data-item="0">0%</div>
        </div>
    </div>
</div>

<div class="wrap" id="at-import-page" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_tradedoubler_import_wpnonce"); ?>">
	<div class="at-inner">
		<h1><?php _e('AffiliateTheme Import » Tradedoubler', 'affiliatetheme-tradedoubler'); ?></h1>

		<div id="checkConnection"></div>

		<h2 class="nav-tab-wrapper" id="at-api-tabs">
			<a class="nav-tab nav-tab-active" id="settings-tab" href="#top#settings"><?php _e('Einstellungen', 'affiliatetheme-tradedoubler'); ?></a>
			<a class="nav-tab" id="search-tab" href="#top#search"><?php _e('Suche', 'affiliatetheme-tradedoubler'); ?></a>
            <a class="nav-tab" id="apilog-tab" href="#top#apilog"><?php _e('API Log', 'affiliatetheme-tradedoubler'); ?></a>
			<a class="nav-tab" id="buttons-tab" href="#top#buttons"><?php _e('Buttons', 'affiliatetheme-tradedoubler'); ?></a>
			<a class="nav-tab" href="https://affiliatetheme.io/forum/foren/support/schnittstellen/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php _e('Hilfe', 'affiliatetheme-tradedoubler'); ?></a>
		</h2>
		
		<div class="tabwrapper">
			<!-- START: Settings Tab-->
			<div id="settings" class="at-api-tab active">
				<div id="at-import-settings" class="metabox-holder postbox">
					<h3 class="hndle"><span><?php _e('Einstellungen', 'affiliatetheme-tradedoubler'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_options; ?>_form" name="<?php echo $plugin_options; ?>_form">
							<?php settings_fields( $plugin_options ); ?>
							<?php do_settings_sections( $plugin_options ); ?>
							<div class="form-container">
                                <div class="form-group">
                                    <label for="tradedoubler_products_token"><?php _e('Tradedoubler Produkt API Token', 'affiliatetheme-tradedoubler'); ?> <sup>*</sup></label>
                                    <input type="password" name="tradedoubler_products_token" value="<?php echo get_option('tradedoubler_products_token'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="tradedoubler_post_status"><?php _e('Produktstatus', 'affiliatetheme-tradedoubler'); ?></label>
                                    <?php $selected_tradedoubler_post_status = get_option('tradedoubler_post_status'); ?>
                                    <select name="tradedoubler_post_status" id="tradedoubler_post_status">
                                        <option value="publish"><?php _e('Veröffentlicht', 'affiliatetheme-tradedoubler'); ?></option>
                                        <option value="draft" <?php if($selected_tradedoubler_post_status == "draft") echo 'selected'; ?>><?php _e('Entwurf', 'affiliatetheme-tradedoubler'); ?></option>
                                    </select>
                                    <p class="form-hint"><?php _e('Du kannst Produkte sofort veröffentlichen oder als Entwurf anlegen.', 'affiliatetheme-tradedoubler'); ?></p>
                                </div>
								<div class="form-group">
									<label for="tradedoubler_import_description"><?php _e('Beschreibung', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="checkbox" name="tradedoubler_import_description" id="tradedoubler_import_description" value="1" <?php if('1' == get_option('tradedoubler_import_description')) echo 'checked'; ?>> <?php _e('Produktbeschreibung importieren', 'affiliatetheme-tradedoubler'); ?>
								</div>
								<h3><?php _e('Einstellungen für den Update-Prozess', 'affiliatetheme-tradedoubler'); ?></h3>
								<div class="form-group">
									<label for="tradedoubler_update_ean"><?php _e('EAN', 'affiliatetheme-tradedoubler'); ?></label>
									<?php $selected_tradedoubler_update_ean = get_option('tradedoubler_update_ean'); ?>
									<select name="tradedoubler_update_ean" id="tradedoubler_update_ean">
										<option value="yes" <?php if($selected_tradedoubler_update_ean == 'yes' || $selected_tradedoubler_update_ean == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-tradedoubler'); ?></option>
										<option value="no" <?php if($selected_tradedoubler_update_ean == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-tradedoubler'); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label for="tradedoubler_update_price"><?php _e('Preise', 'affiliatetheme-tradedoubler'); ?></label>
									<?php $selected_tradedoubler_update_price = get_option('tradedoubler_update_price'); ?>
									<select name="tradedoubler_update_price" id="tradedoubler_update_price">
										<option value="yes" <?php if($selected_tradedoubler_update_price == 'yes' || $selected_tradedoubler_update_price == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-tradedoubler'); ?></option>
										<option value="no" <?php if($selected_tradedoubler_update_price == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-tradedoubler'); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label for="tradedoubler_update_url"><?php _e('URL', 'affiliatetheme-tradedoubler'); ?></label>
									<?php $selected_tradedoubler_update_url = get_option('tradedoubler_update_url'); ?>
									<select name="tradedoubler_update_url" id="tradedoubler_update_url">
										<option value="yes" <?php if($selected_tradedoubler_update_url == 'yes' || $selected_tradedoubler_update_url == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-tradedoubler'); ?></option>
										<option value="no" <?php if($selected_tradedoubler_update_url == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-tradedoubler'); ?></option>
									</select>
								</div>
                                <div class="form-group">
                                    <label for="tradedoubler_check_product_unique"><?php _e('Produkt überprüfen', 'affiliatetheme-tradedoubler'); ?></label>
                                    <?php $selected_tradedoubler_check_product_unique = get_option('tradedoubler_check_product_unique'); ?>
                                    <select name="tradedoubler_check_product_unique" id="tradedoubler_check_product_unique">
                                        <option value="no" <?php if($selected_tradedoubler_check_product_unique == 'no' || $selected_tradedoubler_check_product_unique == '') echo 'selected'; ?>><?php _e('Nicht prüfen', 'affiliatetheme-tradedoubler'); ?></option>
                                        <option value="log" <?php if($selected_tradedoubler_check_product_unique == 'log') echo 'selected'; ?>><?php _e('Nur in API-Log vermerken', 'affiliatetheme-tradedoubler'); ?></option>
                                        <option value="draft" <?php if($selected_tradedoubler_check_product_unique == 'draft') echo 'selected'; ?>><?php _e('Produkt als Entwurf setzen', 'affiliatetheme-tradedoubler'); ?></option>
                                        <option value="remove" <?php if($selected_tradedoubler_check_product_unique == 'remove') echo 'selected'; ?>><?php _e('Tradedoubler Preis vom Preisvergleich entfernen', 'affiliatetheme-tradedoubler'); ?></option>
                                    </select>
                                </div>
								<div class="form-group">
									<?php submit_button(); ?>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- END: Settings Tab-->

			<!-- START: Search Tab-->
			<div id="search" class="at-api-tab">
				<div id="at-import-window" class="metabox-holder postbox">
					<h3 class="hndle"><span><?php _e('Tradedoubler durchsuchen', 'affiliatetheme-tradedoubler'); ?></span></h3>
					<div class="inside">
						<div class="form-container">
							<div class="col-left">
								<div class="form-group">
									<label for="search"><?php _e('Suche nach Keyword', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="text" name="search" id="search">
								</div>

								<div class="form-group">
									<label for="search_ean"><?php _e('Suche nach EAN', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="text" name="search_ean" id="search_ean">
									<p class="form-hint"><?php _e('Bei der EAN Suche sind alle Filter (auf der rechten Seite) wirkungslos.', 'affiliatetheme-tradedoubler'); ?></p>
								</div>

								<div class="form-group">
									<label for="tradedoubler_shop"><?php _e('Shop', 'affiliatetheme-tradedoubler'); ?></label>
									<select name="tradedoubler_shop[]" id="tradedoubler_shop" multiple="multiple">
										<option>-</option>
									</select>
								</div>

								<div class="form-group" id="category-selector">
									<label><?php _e('Kategorie', 'affiliatetheme-tradedoubler'); ?></label>
									<select name="category" id="category">
										<option value="" selected>-</option>
									</select>
								</div>
							</div>

							<div class="col-right">
								<div class="form-group">
									<label for="min_price"><?php _e('Minimaler Preis', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="number" name="min_price" id="min_price" step="1" value="" />
								</div>

								<div class="form-group">
									<label for="max_price"><?php _e('Maximaler Preis', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="number" name="max_price" id="max_price" step="1" value="" />
								</div>

								<div class="form-group">
									<label for="sort"><?php _e('Sortierung', 'affiliatetheme-tradedoubler'); ?></label>
									<select name="sort" id="sort">
                                        <option value=""><?php _e('Keine', 'affiliatetheme-tradedoubler'); ?></option>
										<option value="price"><?php _e('Preis', 'affiliatetheme-tradedoubler'); ?></option>
										<option value="modificationDate"><?php _e('Letzte Aktualisierung', 'affiliatetheme-tradedoubler'); ?></option>
									</select>
								</div>

								<div class="form-group">
									<label for="order"><?php _e('Reihenfolge', 'affiliatetheme-tradedoubler'); ?></label>
									<select name="order" id="order">
										<option value="Desc" selected><?php _e('Absteigend', 'affiliatetheme-tradedoubler'); ?></option>
										<option value="Asc"><?php _e('Aufsteigend', 'affiliatetheme-tradedoubler'); ?></option>
									</select>
								</div>

								<div class="form-group">
									<label for="items"><?php _e('Produkte pro Seite', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="number" name="items" id="items" step="1" value="25" />
									<p class="form-hint"><?php _e('Maximaler Wert:', 'affiliatetheme-tradedoubler'); ?> 500</p>
								</div>
							</div>

							<div class="clearfix"></div>

							<div class="form-group submit-group">
								<input type="hidden" name="page" id="page" value="1">
								<input type="hidden" name="max-pages" id="max-pages" value="">
								<a href="#" id="search-link" class="button button-primary"><?php _e('Suche', 'affiliatetheme-tradedoubler'); ?></a>
							</div>
						</div>
						
						<div id="info-title">
							
						</div>
						
						<div class="page-links" style="margin-bottom:15px;">
							<a href="#" class="prev-page button">« <?php _e('Vorherige Seite', 'affiliatetheme-tradedoubler'); ?></a>
							<a href="#" class="next-page button"><?php _e('Nächste Seite', 'affiliatetheme-tradedoubler'); ?> »</a>
						</div>
										
						<table class="wp-list-table widefat fixed products">
							<thead>
								<tr>
									<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
										<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-tradedoubler'); ?></label>
										<input id="cb-select-all-1" type="checkbox">
									</th>
									<th scope="col" id="id" class="manage-column column-id" style="width:110px;">
										<span><?php _e('ID', 'affiliatetheme-tradedoubler'); ?></span>
									</th>
									<th scope="col" id="productid" class="manage-column column-productid" style="width:110px;">
										<span><?php _e('Artikelnummer', 'affiliatetheme-tradedoubler'); ?></span>
									</th>
									<th scope="col" id="image" class="manage-column column-image" style="width:100px;">
										<span><?php _e('Vorschau', 'affiliatetheme-tradedoubler'); ?></span>
									</th>
									<th scope="col" id="title" class="manage-column column-title" style="">
										<span><?php _e('Name', 'affiliatetheme-tradedoubler'); ?></span>
									</th>
									<th scope="col" id="description" class="manage-column column-shop" style="">
										<span><?php _e('Shop', 'affiliatetheme-tradedoubler'); ?></span>
									</th>
									<th scope="col" id="price" class="manage-column column-price" style="">
										<span><?php _e('Preis', 'affiliatetheme-tradedoubler'); ?></span>
									</th>
									<th scope="col" id="category" class="manage-column column-category" style="">
										<span><?php _e('Kategorie', 'affiliatetheme-tradedoubler'); ?></span>
									</th>
									<th scope="col" id="actions" class="manage-column column-action" style="">
										<span><?php _e('Aktion', 'affiliatetheme-tradedoubler'); ?></span>
									</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td colspan="9">
										<?php
										if(get_products_multiselect_tax_form()) {
											echo '<div class="taxonomy-select">' . get_products_multiselect_tax_form() . '</div>';
										}
										?>
										<div class="clearfix"></div>
										<a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-tradedoubler'); ?></a>
									</td>
								</tr>
							</tfoot>
							<tbody id="results"></tbody>
						</table>

						<div class="page-links" style="margin-top:15px;">
							<a href="#" class="prev-page button">« <?php _e('Vorherige Seite', 'affiliatetheme-tradedoubler'); ?></a>
							<a href="#" class="next-page button"><?php _e('Nächste Seite', 'affiliatetheme-tradedoubler'); ?> »</a>
						</div>
						
						<?php add_thickbox(); ?>
						<div id="product_lookup" style="display:none;">
						     <p>
						          endcore rocks!
						     </p>
						</div>
					</div>
				</div>
			</div>
			<!-- END: Search Tab-->

            <!-- START: API Log Tab-->
            <div id="apilog" class="at-api-tab active">
                <div id="at-import-settings" class="metabox-holder postbox">
                    <h3 class="hndle"><span><?php _e('API Log', 'affiliatetheme-tradedoubler'); ?></span></h3>
                    <div class="inside">
                        <p><?php _e('Hier werden dir die letzten 200 Einträge der API log angezeigt.', 'affiliatetheme-tradedoubler'); ?></p>
                        <p><a href="" class="clear-api-log button" data-type="tradedoubler" data-hash="<?php echo TRADEDOUBLER_CRON_HASH; ?>"><?php _e('Log löschen', 'affiliatetheme-tradedoubler'); ?></a></p>

                        <table class="apilog">
                            <thead>
                            <tr>
                                <th><?php _e('Datum', 'affiliatetheme-tradedoubler') ?></th>
                                <th><?php _e('Typ', 'affiliatetheme-tradedoubler') ?></th>
                                <th><?php _e('Nachricht', 'affiliatetheme-tradedoubler') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $log = get_option('at_tradedoubler_api_log');
                            if($log) {
                                $log = array_reverse($log);

                                foreach($log as $item) {
                                    ?>
                                    <tr>
                                        <td><?php echo date('d.m.Y H:i:s', $item['time']); ?></td>
                                        <td>
                                            <?php
                                            if('system' != ($item['post_id'])) {
                                                ?><a href="<?php echo admin_url('post.php?post='.$item["post_id"].'&action=edit'); ?>" target="_blank"><?php echo get_the_title($item['post_id']); ?></a><?php
                                            } else {
                                                echo $item['post_id'];
                                            }
                                            ?>
                                        </td>
                                        <td><?php if(is_array($item['msg'])) { print_r($item['msg']); } else { echo $item['msg']; } ?></td>
                                    </tr>
                                <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END: API Log Tab-->

			<!-- START: Buttons Tab-->
			<div id="buttons" class="at-api-tab">
				<div id="at-import-settings" class="metabox-holder postbox">
					<h3 class="hndle"><span><?php _e('Buttons', 'affiliatetheme-tradedoubler'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_button_options; ?>_form" name="<?php echo $plugin_button_options; ?>_form">
							<?php settings_fields($plugin_button_options); ?>
							<?php do_settings_sections( $plugin_button_options ); ?>
							<p class="hint"><?php _e('Wenn du für CJ Produkte spezielle Button-Texte ausgeben möchtest, kannst du diese hier angeben. <br>Für den Kaufen Button kannst du <u><strong>%s</strong></u> als Platzhalter für den Shopnamen einbinden, sofern angegeben.', 'affiliatetheme-tradedoubler'); ?></p>
							<div class="form-container">
								<div class="form-group">
									<label for="tradedoubler_buy_short_button"><?php _e('Kaufen Button (kurz)', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="text" name="tradedoubler_buy_short_button" value="<?php echo (get_option('tradedoubler_buy_short_button') ? htmlentities(get_option('tradedoubler_buy_short_button')) : __('Kaufen', 'affiliatetheme-tradedoubler')); ?>" />
								</div>
								<div class="form-group">
									<label for="tradedoubler_buy_button"><?php _e('Kaufen Button', 'affiliatetheme-tradedoubler'); ?></label>
									<input type="text" name="tradedoubler_buy_button" value="<?php echo (get_option('tradedoubler_buy_button') ? htmlentities(get_option('tradedoubler_buy_button')) : __('Jetzt bei %s kaufen', 'affiliatetheme-tradedoubler')); ?>" />
								</div>
								<div class="form-group">
									<?php submit_button(); ?>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- END: Buttons Tab-->
		</div>		
	</div>

    <div class="afs_ads">&nbsp;</div>
</div>