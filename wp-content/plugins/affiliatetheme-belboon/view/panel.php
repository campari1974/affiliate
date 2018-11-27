<?php date_default_timezone_set( 'Europe/Berlin' ); ?>
<div class="at-ajax-loader">
	<div class="inner">
		<p></p>

		<div class="progress">
			<div class="progress-bar" style="width:0%;" data-item="0">0%</div>
		</div>
	</div>
</div>

<div class="wrap" id="at-import-page" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_belboon_import_wpnonce"); ?>">
	<div class="at-inner">
		<h1><?php _e('AffiliateTheme Import » Belboon', 'affiliatetheme-belboon'); ?></h1>

		<div id="checkConnection"></div>

		<h2 class="nav-tab-wrapper" id="at-api-tabs">
			<a class="nav-tab nav-tab-active" id="settings-tab" href="#top#settings"><?php _e('Einstellungen', 'affiliatetheme-belboon'); ?></a>
			<a class="nav-tab" id="search-tab" href="#top#search"><?php _e('Suche', 'affiliatetheme-belboon'); ?></a>
            <a class="nav-tab" id="apilog-tab" href="#top#apilog"><?php _e('API Log', 'affiliatetheme-belboon'); ?></a>
			<a class="nav-tab" id="buttons-tab" href="#top#buttons"><?php _e('Buttons', 'affiliatetheme-belboon'); ?></a>
			<a class="nav-tab" href="http://affiliatetheme.io/forum/foren/support/belboon-plugin/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php _e('Hilfe', 'affiliatetheme-belboon'); ?></a>
		</h2>
		
		<div class="tabwrapper">
			<!-- START: Settings Tab-->
			<div id="settings" class="at-api-tab active">
				<div id="at-import-settings" class="metabox-holder postbox">
					<h3 class="hndle"><span><?php _e('Einstellungen', 'affiliatetheme-belboon'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_options; ?>_form" name="<?php echo $plugin_options; ?>_form">
							<?php settings_fields( $plugin_options ); ?>
							<?php do_settings_sections( $plugin_options ); ?>
							<div class="form-container">
								<div class="form-group">
									<label for="belboon_user"><?php _e('Benutzername', 'affiliatetheme-belboon'); ?> <sup>*</sup></label>
									<input type="text" name="belboon_user" value="<?php echo get_option('belboon_user'); ?>" />
								</div>
								<div class="form-group">
									<label for="belboon_password"><?php _e('Passwort', 'affiliatetheme-belboon'); ?> <sup>*</sup></label>
									<input type="password" name="belboon_password" value="<?php echo get_option('belboon_password'); ?>" />
								</div>
                                <div class="form-group">
                                    <label for="belboon_post_status"><?php _e('Produktstatus', 'affiliatetheme-belboon'); ?></label>
                                    <?php $selected_belboon_post_status = get_option('belboon_post_status'); ?>
                                    <select name="belboon_post_status" id="belboon_post_status">
                                        <option value="publish"><?php _e('Veröffentlicht', 'affiliatetheme-belboon'); ?></option>
                                        <option value="draft" <?php if($selected_belboon_post_status == "draft") echo 'selected'; ?>><?php _e('Entwurf', 'affiliatetheme-belboon'); ?></option>
                                    </select>
                                    <p class="form-hint"><?php _e('Du kannst Produkte sofort veröffentlichen oder als Entwurf anlegen.', 'affiliatetheme-belboon'); ?></p>
                                </div>
								<div class="form-group">
									<label for="belboon_import_description"><?php _e('Beschreibung', 'affiliatetheme-belboon'); ?></label>
									<input type="checkbox" name="belboon_import_description" id="belboon_import_description" value="1" <?php if('1' == get_option('belboon_import_description')) echo 'checked'; ?>> <?php _e('Produktbeschreibung importieren', 'affiliatetheme-belboon'); ?>
								</div>
								<h3><?php _e('Einstellungen für den Update-Prozess', 'affiliatetheme-belboon'); ?></h3>
								<div class="form-group">
									<label for="belboon_update_ean"><?php _e('EAN', 'affiliatetheme-belboon'); ?></label>
									<?php $selected_belboon_update_ean = get_option('belboon_update_ean'); ?>
									<select name="belboon_update_ean" id="belboon_update_ean">
										<option value="yes" <?php if($selected_belboon_update_ean == 'yes' || $selected_belboon_update_ean == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-belboon'); ?></option>
										<option value="no" <?php if($selected_belboon_update_ean == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-belboon'); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label for="belboon_update_price"><?php _e('Preise', 'affiliatetheme-belboon'); ?></label>
									<?php $selected_belboon_update_price = get_option('belboon_update_price'); ?>
									<select name="belboon_update_price" id="belboon_update_price">
										<option value="yes" <?php if($selected_belboon_update_price == 'yes' || $selected_belboon_update_price == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-belboon'); ?></option>
										<option value="no" <?php if($selected_belboon_update_price == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-belboon'); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label for="belboon_update_url"><?php _e('URL', 'affiliatetheme-belboon'); ?></label>
									<?php $selected_belboon_update_url = get_option('belboon_update_url'); ?>
									<select name="belboon_update_url" id="belboon_update_url">
										<option value="yes" <?php if($selected_belboon_update_url == 'yes' || $selected_belboon_update_url == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-belboon'); ?></option>
										<option value="no" <?php if($selected_belboon_update_url == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-belboon'); ?></option>
									</select>
								</div>
                                <div class="form-group">
                                    <label for="belboon_check_product_unique"><?php _e('Produkt überprüfen', 'affiliatetheme-belboon'); ?></label>
                                    <?php $selected_belboon_check_product_unique = get_option('belboon_check_product_unique'); ?>
                                    <select name="belboon_check_product_unique" id="belboon_check_product_unique">
                                        <option value="no" <?php if($selected_belboon_check_product_unique == 'no' || $selected_belboon_check_product_unique == '') echo 'selected'; ?>><?php _e('Nicht prüfen', 'affiliatetheme-belboon'); ?></option>
                                        <option value="log" <?php if($selected_belboon_check_product_unique == 'log') echo 'selected'; ?>><?php _e('Nur in API-Log vermerken', 'affiliatetheme-belboon'); ?></option>
                                        <option value="draft" <?php if($selected_belboon_check_product_unique == 'draft') echo 'selected'; ?>><?php _e('Produkt als Entwurf setzen', 'affiliatetheme-belboon'); ?></option>
                                        <option value="remove" <?php if($selected_belboon_check_product_unique == 'remove') echo 'selected'; ?>><?php _e('Belboon Preis vom Preisvergleich entfernen', 'affiliatetheme-belboon'); ?></option>
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
					<h3 class="hndle"><span><?php _e('Belboon durchsuchen', 'affiliatetheme-belboon'); ?></span></h3>
					<div class="inside">
						<div class="form-container">
							<div class="form-group">
								<label for="search"><?php _e('Suche', 'affiliatetheme-belboon'); ?></label>
								<input type="text" name="search" id="search">
							</div>

							<div class="form-group">
								<label for="belboon_platform"><?php _e('Plattform', 'affiliatetheme-belboon'); ?></label>
								<select name="belboon_platform" id="belboon_platform" >
									<option>-</option>
								</select>
							</div>

							<div class="form-group">
								<label for="belboon_shop"><?php _e('Shop', 'affiliatetheme-belboon'); ?></label>
								<select name="belboon_shop[]" id="belboon_shop" multiple="multiple">
									<option>-</option>
								</select>
							</div>

							<div class="form-group">
								<label for="min_price"><?php _e('Minimaler Preis', 'affiliatetheme-belboon'); ?></label>
								<input type="number" name="min_price" id="min_price" value="" />
							</div>

							<div class="form-group">
								<label for="max_price"><?php _e('Maximaler Preis', 'affiliatetheme-belboon'); ?></label>
								<input type="number" name="max_price" id="max_price" value="" />
							</div>

							<div class="form-group">
								<label for="sort"><?php _e('Sortierung', 'affiliatetheme-belboon'); ?></label>
								<select name="sort" id="sort">
									<option value="belboon_productnumber">-</option>
									<option value="currentprice"><?php _e('Preis', 'affiliatetheme-belboon'); ?></option>
									<option value="lastupdate"><?php _e('Zuletzt aktualisiert', 'affiliatetheme-belboon'); ?></option>
									<option value="productname"><?php _e('Produktname', 'affiliatetheme-belboon'); ?></option>
								</select>
							</div>

							<div class="form-group">
								<label for="order"><?php _e('Reihenfolge', 'affiliatetheme-belboon'); ?></label>
								<select name="order" id="order">
									<option value="DESC"><?php _e('Absteigend', 'affiliatetheme-belboon'); ?></option>
									<option value="ASC" selected><?php _e('Aufsteigend', 'affiliatetheme-belboon'); ?></option>
								</select>
							</div>

							<div class="form-group submit-group">
								<input type="hidden" name="page" id="page" value="1">
								<input type="hidden" name="max-pages" id="max-pages" value="">
								<a href="#" id="search-link" class="button button-primary"><?php _e('Suche', 'affiliatetheme-belboon'); ?></a>
							</div>
							
							<div class="clearfix"></div>
						</div>
						
						<div id="info-title">
							
						</div>
						
						<div class="page-links" style="margin-bottom:15px;">
							<a href="#" class="prev-page button">« <?php _e('Vorherige Seite', 'affiliatetheme-belboon'); ?></a>
							<a href="#" class="next-page button"><?php _e('Nächste Seite', 'affiliatetheme-belboon'); ?> »</a>
						</div>
										
						<table class="wp-list-table widefat fixed products">
							<colgroup>
								<col width=""></col>
								<col width="15%"></col>
								<col width="10%"></col>
								<col width="15%"></col>
								<col width="25%"></col>
								<col width="15%"></col>
								<col width="12%"></col>
								<col width="8%"></col>
							</colgroup>
							<thead>
								<tr>
									<th scope="col" id="cb" class="manage-column column-cb check-column">
										<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-belboon'); ?></label><input id="cb-select-all-1" type="checkbox">
									</th>
									<th scope="col" id="id" class="manage-column column-id">
										<span><?php _e('ID', 'affiliatetheme-belboon'); ?></span>
									</th>
									<th scope="col" id="productid" class="manage-column column-productid" >
										<span><?php _e('Artikelnummer', 'affiliatetheme-belboon'); ?></span>
									</th>
									<th scope="col" id="image" class="manage-column column-image">
										<span><?php _e('Vorschau', 'affiliatetheme-belboon'); ?></span>
									</th>
									<th scope="col" id="title" class="manage-column column-title" >
										<span><?php _e('Name', 'affiliatetheme-belboon'); ?></span>
									</th>
									<th scope="col" id="description" class="manage-column column-shop">
										<span><?php _e('Shop', 'affiliatetheme-belboon'); ?></span>
									</th>
									<th scope="col" id="price" class="manage-column column-price">
										<span><?php _e('Preis', 'affiliatetheme-belboon'); ?></span>
									</th>
									<th scope="col" id="actions" class="manage-column column-action">
										<span><?php _e('Aktion', 'affiliatetheme-belboon'); ?></span>
									</th>
								</tr>
							</thead>
							<tfoot>
							<tr>
								<td colspan="8">
									<?php
									if(get_products_multiselect_tax_form()) {
										echo '<div class="taxonomy-select">' . get_products_multiselect_tax_form() . '</div>';
									}
									?>
									<div class="clearfix"></div>
									<a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-belboon'); ?></a>
								</td>
							</tr>
							</tfoot>
							<tbody id="results"></tbody>
						</table>

						<div class="page-links" style="margin-top:15px;">
							<a href="#" class="prev-page button">« <?php _e('Vorherige Seite', 'affiliatetheme-belboon'); ?></a>
							<a href="#" class="next-page button"><?php _e('Nächste Seite', 'affiliatetheme-belboon'); ?> »</a>
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
                    <h3 class="hndle"><span><?php _e('API Log', 'affiliatetheme-belboon'); ?></span></h3>
                    <div class="inside">
                        <p><?php _e('Hier werden dir die letzten 200 Einträge der API log angezeigt.', 'affiliatetheme-belboon'); ?></p>
                        <p><a href="" class="clear-api-log button" data-type="belboon" data-hash="<?php echo BBOON_CRON_HASH; ?>"><?php _e('Log löschen', 'affiliatetheme-belboon'); ?></a></p>

                        <table class="apilog">
                            <thead>
                            <tr>
                                <th><?php _e('Datum', 'affiliatetheme-belboon') ?></th>
                                <th><?php _e('Typ', 'affiliatetheme-belboon') ?></th>
                                <th><?php _e('Nachricht', 'affiliatetheme-belboon') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $log = get_option('at_belboon_api_log');
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
					<h3 class="hndle"><span><?php _e('Buttons', 'affiliatetheme-belboon'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_button_options; ?>_form" name="<?php echo $plugin_button_options; ?>_form">
							<?php settings_fields($plugin_button_options); ?>
							<?php do_settings_sections( $plugin_button_options ); ?>
							<p class="hint"><?php _e('Wenn du für belboon Produkte spezielle Button-Texte ausgeben möchtest, kannst du diese hier angeben. <br>Für den Kaufen Button kannst du <u><strong>%s</strong></u> als Platzhalter für den Shopnamen einbinden, sofern angegeben.</strong>', 'affiliatetheme-belboon'); ?></p>
							<div class="form-container">
								<div class="form-group">
									<label for="belboon_buy_short_button"><?php _e('Kaufen Button (kurz)', 'affiliatetheme-belboon'); ?></label>
									<input type="text" name="belboon_buy_short_button" value="<?php echo (get_option('belboon_buy_short_button') ? get_option('belboon_buy_short_button') : __('Kaufen', 'affiliatetheme-belboon')); ?>" />
								</div>
								<div class="form-group">
									<label for="belboon_buy_button"><?php _e('Kaufen Button', 'affiliatetheme-belboon'); ?></label>
									<input type="text" name="belboon_buy_button" value="<?php echo (get_option('belboon_buy_button') ? get_option('belboon_buy_button') : __('Jetzt bei %s kaufen', 'affiliatetheme-belboon')); ?>" />
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
