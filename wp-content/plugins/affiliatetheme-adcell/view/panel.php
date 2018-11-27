<?php date_default_timezone_set( 'Europe/Berlin' ); ?>
<div class="at-ajax-loader">
	<div class="inner">
		<p></p>

		<div class="progress">
			<div class="progress-bar" style="width:0%;" data-item="0">0%</div>
		</div>
	</div>
</div>

<div class="wrap" id="at-import-page" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_adcell_import_wpnonce"); ?>">
	<div class="at-inner">
		<h1><?php _e('AffiliateTheme Import » adcell', 'affiliatetheme-adcell'); ?></h1>

		<div id="checkConnection"></div>

		<h2 class="nav-tab-wrapper" id="at-api-tabs">
			<a class="nav-tab nav-tab-active" id="settings-tab" href="#top#settings"><?php _e('Einstellungen', 'affiliatetheme-adcell'); ?></a>
			<a class="nav-tab" id="search-tab" href="#top#search"><?php _e('Suche', 'affiliatetheme-adcell'); ?></a>
            <a class="nav-tab" id="apilog-tab" href="#top#apilog"><?php _e('API Log', 'affiliatetheme-adcell'); ?></a>
			<a class="nav-tab" id="buttons-tab" href="#top#buttons"><?php _e('Buttons', 'affiliatetheme-adcell'); ?></a>
			<a class="nav-tab" href="http://affiliatetheme.io/forum/foren/support/adcell-plugin/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php _e('Hilfe', 'affiliatetheme-adcell'); ?></a>
		</h2>
		
		<div class="tabwrapper">
			<!-- START: Settings Tab-->
			<div id="settings" class="at-api-tab active">
				<div id="at-import-settings" class="metabox-holder postbox">
					<h3 class="hndle"><span><?php _e('Einstellungen', 'affiliatetheme-adcell'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_options; ?>_form" name="<?php echo $plugin_options; ?>_form">
							<?php settings_fields( $plugin_options ); ?>
							<?php do_settings_sections( $plugin_options ); ?>
							<div class="form-container">
								<div class="form-group">
									<label for="adcell_user"><?php _e('Benutzername', 'affiliatetheme-adcell'); ?> <sup>*</sup></label>
									<input type="text" name="adcell_user" value="<?php echo get_option('adcell_user'); ?>" />
								</div>
								<div class="form-group">
									<label for="adcell_password"><?php _e('Passwort', 'affiliatetheme-adcell'); ?> <sup>*</sup></label>
									<input type="password" name="adcell_password" value="<?php echo get_option('adcell_password'); ?>" />
								</div>
                                <div class="form-group">
                                    <label for="adcell_post_status"><?php _e('Produktstatus', 'affiliatetheme-adcell'); ?></label>
                                    <?php $selected_adcell_post_status = get_option('adcell_post_status'); ?>
                                    <select name="adcell_post_status" id="adcell_post_status">
                                        <option value="publish"><?php _e('Veröffentlicht', 'affiliatetheme-adcell'); ?></option>
                                        <option value="draft" <?php if($selected_adcell_post_status == "draft") echo 'selected'; ?>><?php _e('Entwurf', 'affiliatetheme-adcell'); ?></option>
                                    </select>
                                    <p class="form-hint"><?php _e('Du kannst Produkte sofort veröffentlichen oder als Entwurf anlegen.', 'affiliatetheme-adcell'); ?></p>
                                </div>
								<div class="form-group">
									<label for="adcell_import_description"><?php _e('Beschreibung', 'affiliatetheme-adcell'); ?></label>
									<input type="checkbox" name="adcell_import_description" id="adcell_import_description" value="1" <?php if('1' == get_option('adcell_import_description')) echo 'checked'; ?>> <?php _e('Produktbeschreibung importieren', 'affiliatetheme-adcell'); ?>
								</div>
                                <div class="form-group">
                                    <label for="adcell_check_product_unique"><?php _e('Produkt überprüfen', 'affiliatetheme-adcell'); ?></label>
                                    <?php $selected_adcell_check_product_unique = get_option('adcell_check_product_unique'); ?>
                                    <select name="adcell_check_product_unique" id="adcell_check_product_unique">
                                        <option value="no" <?php if($selected_adcell_check_product_unique == 'no' || $selected_adcell_check_product_unique == '') echo 'selected'; ?>><?php _e('Nicht prüfen', 'affiliatetheme-adcell'); ?></option>
                                        <option value="log" <?php if($selected_adcell_check_product_unique == 'log') echo 'selected'; ?>><?php _e('Nur in API-Log vermerken', 'affiliatetheme-adcell'); ?></option>
                                        <option value="draft" <?php if($selected_adcell_check_product_unique == 'draft') echo 'selected'; ?>><?php _e('Produkt als Entwurf setzen', 'affiliatetheme-adcell'); ?></option>
                                        <option value="remove" <?php if($selected_adcell_check_product_unique == 'remove') echo 'selected'; ?>><?php _e('Adcell Preis vom Preisvergleich entfernen', 'affiliatetheme-adcell'); ?></option>
                                    </select>
                               </div>
								<input type="hidden" name="adcell_nid" value="9" />
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
					<h3 class="hndle"><span><?php _e('adcell durchsuchen', 'affiliatetheme-adcell'); ?></span></h3>
					<div class="inside">
						<div class="form-container">
							<div class="form-group">
								<label for="search"><?php _e('Suche', 'affiliatetheme-adcell'); ?></label>
								<input type="text" name="search" id="search">
							</div>

							<div class="form-group">
								<label for="program"><?php _e('Partnerprogramm', 'affiliatetheme-adcell'); ?></label>
								<select name="program" id="program">
									<option>-</option>
								</select>
							</div>

							<div class="form-group">
								<label for="promo"><?php _e('CSV-Datei', 'affiliatetheme-adcell'); ?></label>
								<select name="promo" id="promo">
									<option><?php _e('Keine CSV-Daten gefunden.', 'affiliatetheme-adcell'); ?></option>
								</select>
							</div>

							<div class="form-group submit-group">
								<a href="#" id="search-link" class="button button-primary"><?php _e('Suche', 'affiliatetheme-adcell'); ?></a>
							</div>
							
							<div class="clearfix"></div>

							<div class="alert alert-info"><?php _e('Die Suche kann, je nach Größe der CSV-Datei, etwas länger dauern.', 'affiliatetheme-adcell'); ?></div>
						</div>
						
						<div id="info-title">
							
						</div>
										
						<table class="wp-list-table widefat fixed products">
							<thead>
								<tr>
									<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
										<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-adcell'); ?></label>
										<input id="cb-select-all-1" type="checkbox">
									</th>
									<th scope="col" id="id" class="manage-column column-id" style="width:110px;">
										<span><?php _e('ID', 'affiliatetheme-adcell'); ?></span>
									</th>
									<th scope="col" id="productid" class="manage-column column-productid" style="width:110px;">
										<span><?php _e('Artikelnummer', 'affiliatetheme-adcell'); ?></span>
									</th>
									<th scope="col" id="image" class="manage-column column-image" style="width:100px;">
										<span><?php _e('Vorschau', 'affiliatetheme-adcell'); ?></span>
									</th>
									<th scope="col" id="title" class="manage-column column-title" style="">
										<span><?php _e('Name', 'affiliatetheme-adcell'); ?></span>
									</th>
									<th scope="col" id="price" class="manage-column column-price" style="">
										<span><?php _e('Preis', 'affiliatetheme-adcell'); ?></span>
									</th>
									<th scope="col" id="actions" class="manage-column column-action" style="">
										<span><?php _e('Aktion', 'affiliatetheme-adcell'); ?></span>
									</th>
								</tr>
							</thead>
							<tfoot>
							<tr>
								<td colspan="7">
									<?php
									if(get_products_multiselect_tax_form()) {
										echo '<div class="taxonomy-select">' . get_products_multiselect_tax_form() . '</div>';
									}
									?>
									<div class="clearfix"></div>
									<a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-adcell'); ?></a>
								</td>
							</tr>
							</tfoot>
							<tbody id="results"></tbody>
						</table>
						
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
                    <h3 class="hndle"><span><?php _e('API Log', 'affiliatetheme-adcell'); ?></span></h3>
                    <div class="inside">
                        <p><?php _e('Hier werden dir die letzten 200 Einträge der API log angezeigt.', 'affiliatetheme-adcell'); ?></p>
                        <p><a href="" class="clear-api-log button" data-type="adcell" data-hash="<?php echo ADCELL_CRON_HASH; ?>"><?php _e('Log löschen', 'affiliatetheme-adcell'); ?></a></p>

                        <table class="apilog">
                            <thead>
                            <tr>
                                <th><?php _e('Datum', 'affiliatetheme-adcell') ?></th>
                                <th><?php _e('Typ', 'affiliatetheme-adcell') ?></th>
                                <th><?php _e('Nachricht', 'affiliatetheme-adcell') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $log = get_option('at_adcell_api_log');
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
					<h3 class="hndle"><span><?php _e('Buttons', 'affiliatetheme-adcell'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_button_options; ?>_form" name="<?php echo $plugin_button_options; ?>_form">
							<?php settings_fields($plugin_button_options); ?>
							<?php do_settings_sections( $plugin_button_options ); ?>
							<p class="hint"><?php _e('Wenn du für adcell Produkte spezielle Button-Texte ausgeben möchtest, kannst du diese hier angeben. <br>Für den Kaufen Button kannst du <u><strong>%s</strong></u> als Platzhalter für den Shopnamen einbinden, sofern angegeben.', 'affiliatetheme-adcell'); ?></p>
							<div class="form-container">
								<div class="form-group">
									<label for="adcell_buy_short_button"><?php _e('Kaufen Button (kurz)', 'affiliatetheme-adcell'); ?></label>
									<input type="text" name="adcell_buy_short_button" value="<?php echo (get_option('adcell_buy_short_button') ? htmlentities(get_option('adcell_buy_short_button')) : __('Kaufen', 'affiliatetheme-adcell')); ?>" />
								</div>
								<div class="form-group">
									<label for="adcell_buy_button"><?php _e('Kaufen Button', 'affiliatetheme-adcell'); ?></label>
									<input type="text" name="adcell_buy_button" value="<?php echo (get_option('adcell_buy_button') ? htmlentities(get_option('adcell_buy_button')) : __('Jetzt kaufen', 'affiliatetheme-adcell')); ?>" />
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
