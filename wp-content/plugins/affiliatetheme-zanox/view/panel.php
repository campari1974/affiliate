<?php date_default_timezone_set( 'Europe/Berlin' ); ?>
<div class="at-ajax-loader">
    <div class="inner">
        <p></p>

        <div class="progress">
            <div class="progress-bar" style="width:0%;" data-item="0">0%</div>
        </div>
    </div>
</div>

<div class="wrap" id="at-import-page" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_zanox_import_wpnonce"); ?>">
	<div class="at-inner">
		<h1><?php _e('AffiliateTheme Import » Zanox', 'affiliatetheme-zanox'); ?></h1>
		
		<div id="checkConnection"></div>

		<h2 class="nav-tab-wrapper" id="at-api-tabs">
			<a class="nav-tab nav-tab-active" id="settings-tab" href="#top#settings"><?php _e('Einstellungen', 'affiliatetheme-zanox'); ?></a>
			<a class="nav-tab" id="search-tab" href="#top#search"><?php _e('Suche', 'affiliatetheme-zanox'); ?></a>
			<a class="nav-tab" id="apilog-tab" href="#top#apilog"><?php _e('API Log', 'affiliatetheme-zanox'); ?></a>
			<a class="nav-tab" id="buttons-tab" href="#top#buttons"><?php _e('Buttons', 'affiliatetheme-zanox'); ?></a>
			<a class="nav-tab" href="http://affiliatetheme.io/forum/foren/support/zanox-plugin/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php _e('Hilfe', 'affiliatetheme-zanox'); ?></a>
		</h2>

		<div class="tabwrapper">
			<!-- START: Settings Tab-->
			<div id="settings" class="at-api-tab active">
				<div id="at-import-settings" class="metabox-holder postbox">
					<h3 class="hndle"><span><?php _e('Einstellungen', 'affiliatetheme-zanox'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_options; ?>_form" name="<?php echo $plugin_options; ?>_form">
							<?php settings_fields($plugin_options); ?>
							<?php do_settings_sections( $plugin_options ); ?>
							<div class="form-container">
								<div class="form-group">
									<label for="zanox_connect_id"><?php _e('Connect ID', 'affiliatetheme-zanox'); ?> <sup>*</sup></label>
									<input type="text" name="zanox_connect_id" value="<?php echo get_option('zanox_connect_id'); ?>" />    
								</div>
								<div class="form-group">
									<label for="zanox_secret_key"><?php _e('Secret Key', 'affiliatetheme-zanox'); ?> <sup>*</sup></label>
									<input type="text" name="zanox_secret_key" value="<?php echo get_option('zanox_secret_key'); ?>" />
								</div>
								<div class="form-group">
									<label for="zanox_country"><?php _e('Land', 'affiliatetheme-zanox'); ?> <sup>*</sup></label>
                                    <select name="zanox_country">
										<?php echo at_zanox_get_countries(); ?>
                                    </select>
								</div>
                                <div class="form-group">
                                    <label for="zanox_post_status"><?php _e('Produktstatus', 'affiliatetheme-zanox'); ?></label>
                                    <?php $selected_zanox_post_status = get_option('zanox_post_status'); ?>
                                    <select name="zanox_post_status" id="zanox_post_status">
                                        <option value="publish"><?php _e('Veröffentlicht', 'affiliatetheme-zanox'); ?></option>
                                        <option value="draft" <?php if($selected_zanox_post_status == "draft") echo 'selected'; ?>><?php _e('Entwurf', 'affiliatetheme-zanox'); ?></option>
                                    </select>
                                    <p class="form-hint"><?php _e('Du kannst Produkte sofort veröffentlichen oder als Entwurf anlegen.', 'affiliatetheme-zanox'); ?></p>
                                </div>
								<div class="form-group">
									<label for="zanox_import_description"><?php _e('Beschreibung', 'affiliatetheme-zanox'); ?></label>
									<input type="checkbox" name="zanox_import_description" id="zanox_import_description" value="1" <?php if('1' == get_option('zanox_import_description')) echo 'checked'; ?>> <?php _e('Produktbeschreibung importieren', 'affiliatetheme-zanox'); ?>
								</div>
								<h3><?php _e('Einstellungen für den Update-Prozess', 'affiliatetheme-zanox'); ?></h3>
								<div class="form-group">
									<label for="zanox_update_price"><?php _e('Preise', 'affiliatetheme-zanox'); ?></label>
									<?php $selected_zanox_update_price = get_option('zanox_update_price'); ?>
									<select name="zanox_update_price" id="zanox_update_price">
										<option value="yes" <?php if($selected_zanox_update_price == 'yes' || $selected_zanox_update_price == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-zanox'); ?></option>
										<option value="no" <?php if($selected_zanox_update_price == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-zanox'); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label for="zanox_update_url"><?php _e('URL', 'affiliatetheme-zanox'); ?></label>
									<?php $selected_zanox_update_url = get_option('zanox_update_url'); ?>
									<select name="zanox_update_url" id="zanox_update_url">
										<option value="yes" <?php if($selected_zanox_update_url == 'yes' || $selected_zanox_update_url == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-zanox'); ?></option>
										<option value="no" <?php if($selected_zanox_update_url == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-zanox'); ?></option>
									</select>
								</div>
                                <div class="form-group">
                                    <label for="zanox_check_product_unique"><?php _e('Produkt überprüfen', 'affiliatetheme-zanox'); ?></label>
                                    <?php $selected_zanox_check_product_unique = get_option('zanox_check_product_unique'); ?>
                                    <select name="zanox_check_product_unique" id="zanox_check_product_unique">
                                        <option value="no" <?php if($selected_zanox_check_product_unique == 'no' || $selected_zanox_check_product_unique == '') echo 'selected'; ?>><?php _e('Nicht prüfen', 'affiliatetheme-zanox'); ?></option>
                                        <option value="log" <?php if($selected_zanox_check_product_unique == 'log') echo 'selected'; ?>><?php _e('Nur in API-Log vermerken', 'affiliatetheme-zanox'); ?></option>
                                        <option value="draft" <?php if($selected_zanox_check_product_unique == 'draft') echo 'selected'; ?>><?php _e('Produkt als Entwurf setzen', 'affiliatetheme-zanox'); ?></option>
                                        <option value="remove" <?php if($selected_zanox_check_product_unique == 'remove') echo 'selected'; ?>><?php _e('Zanox Preis vom Preisvergleich entfernen', 'affiliatetheme-zanox'); ?></option>
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
					<h3 class="hndle"><span><?php _e('Zanox durchsuchen', 'affiliatetheme-zanox'); ?></span></h3>
					<div class="inside">
						<div class="form-container">
							<div class="form-group">
								<label for="search"><?php _e('Suche', 'affiliatetheme-zanox'); ?></label>
								<input type="text" name="search" id="search">
							</div>
							
							<div class="form-group">
								<label for="adspaces"><?php _e('Adspace', 'affiliatetheme-zanox'); ?></label>
								<select name="adspaces" id="adspaces">
									<option value=>-</option>
								</select>
							</div>
							
							<div class="form-group">
								<label for="program"><?php _e('Programm', 'affiliatetheme-zanox'); ?></label>
								<select name="program" id="program">
									<option value="">-</option>
								</select>
							</div>

							<div class="form-group">
								<label for="min_price"><?php _e('Minimaler Preis', 'affiliatetheme-zanox'); ?></label>
								<input type="number" name="min_price" id="min_price" value="" />
							</div>

							<div class="form-group">
								<label for="max_price"><?php _e('Maximaler Preis', 'affiliatetheme-zanox'); ?></label>
								<input type="number" name="max_price" id="max_price" value="" />
							</div>

							<div class="form-group">
								<label for="items"><?php _e('Produkte pro Seite', 'affiliatetheme-zanox'); ?></label>
								<input type="number" name="items" id="items" min="1" max="50" step="1" value="10" />
								<small>1 - 50</small>
							</div>

							<div class="form-group submit-group">
								<input type="hidden" name="page" id="page" value="1">
								<input type="hidden" name="max-pages" id="max-pages" value="">
								<a href="#" id="search-link" class="button button-primary"><?php _e('Suche', 'affiliatetheme-zanox'); ?></a>
							</div>
							
							<div class="clearfix"></div>
						</div>
						
						<div id="info-title">
							
						</div>
						
						<div class="page-links" style="margin-bottom:15px;">
							<button class="prev-page button">« <?php _e('Vorherige Seite', 'affiliatetheme-zanox'); ?></button>
							<button class="next-page button"><?php _e('Nächste Seite', 'affiliatetheme-zanox'); ?> »</button>
						</div>
										
						<table class="wp-list-table widefat fixed products">
							<thead>
								<tr>
									<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
										<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-zanox'); ?></label><input id="cb-select-all-1" type="checkbox">
									</th>
									<th scope="col" id="id" class="manage-column column-asin">
										<span><?php _e('ID', 'affiliatetheme-zanox'); ?></span>
									</th>
									<th scope="col" id="image" class="manage-column column-image">
										<span><?php _e('Vorschau', 'affiliatetheme-zanox'); ?></span>
									</th>
									<th scope="col" id="title" class="manage-column column-title">
										<span><?php _e('Titel', 'affiliatetheme-zanox'); ?></span>
									</th>
									<th scope="col" id="description" class="manage-column column-description" style="">
										<span><?php _e('Beschreibung', 'affiliatetheme-zanox'); ?></span>
									</th>
									<th scope="col" id="price" class="manage-column column-price">
										<span><?php _e('Preis', 'affiliatetheme-zanox'); ?></span>
									</th>
									<th scope="col" id="actions" class="manage-column column-action">
										<span></span>
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
									<a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-zanox'); ?></a>
								</td>
							</tr>
							</tfoot>
							<tbody id="results"></tbody>
						</table>

						<div class="page-links" style="margin-top:15px;">
							<button class="prev-page button">« <?php _e('Vorherige Seite', 'affiliatetheme-zanox'); ?></button>
							<button class="next-page button"><?php _e('Nächste Seite', 'affiliatetheme-zanox'); ?> »</button>
						</div>
						
						<?php add_thickbox(); ?>
						<div id="my-content-id" style="display:none;">
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
					<h3 class="hndle"><span><?php _e('API Log', 'affiliatetheme-zanox'); ?></span></h3>
					<div class="inside">
						<p><?php _e('Hier werden dir die letzten 200 Einträge der API log angezeigt.', 'affiliatetheme-zanox'); ?></p>
                        <p><a href="" class="clear-api-log button" data-type="zanox" data-hash="<?php echo ZANOX_CRON_HASH; ?>"><?php _e('Log löschen', 'affiliatetheme-zanox'); ?></a></p>

						<table class="apilog">
							<thead>
								<tr>
									<th><?php _e('Datum', 'affiliatetheme-zanox') ?></th>
									<th><?php _e('Typ', 'affiliatetheme-zanox') ?></th>
									<th><?php _e('Nachricht', 'affiliatetheme-zanox') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$log = get_option('at_zanox_api_log');
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
					<h3 class="hndle"><span><?php _e('Buttons', 'affiliatetheme-zanox'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_button_options; ?>_form" name="<?php echo $plugin_button_options; ?>_form">
							<?php settings_fields($plugin_button_options); ?>
							<?php do_settings_sections( $plugin_button_options ); ?>
							<p class="hint">
								<?php _e('Wenn du für zanox Produkte spezielle Button-Texte ausgeben möchtest, kannst du diese hier angeben. <br>Für den Kaufen Button kannst du <u><strong>%s</strong></u> als Platzhalter für den Shopnamen einbinden, sofern angegeben.', 'affiliatetheme-zanox'); ?></strong>
							</p>
							<div class="form-container">
								<div class="form-group">
									<label for="zanox_buy_short_button"><?php _e('Kaufen Button (kurz)', 'affiliatetheme-zanox'); ?></label>
									<input type="text" name="zanox_buy_short_button" value="<?php echo (get_option('zanox_buy_short_button') ? htmlentities(get_option('zanox_buy_short_button')) : __('Kaufen', 'affiliatetheme-zanox')); ?>" />
								</div>
								<div class="form-group">
									<label for="zanox_buy_button"><?php _e('Kaufen Button', 'affiliatetheme-zanox'); ?></label>
									<input type="text" name="zanox_buy_button" value="<?php echo (get_option('zanox_buy_button') ? htmlentities(get_option('zanox_buy_button')) : __('Jetzt bei %s kaufen', 'affiliatetheme-zanox')); ?>" />
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
