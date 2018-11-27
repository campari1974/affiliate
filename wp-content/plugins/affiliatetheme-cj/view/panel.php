<?php date_default_timezone_set( 'Europe/Berlin' ); ?>
<div class="at-ajax-loader">
    <div class="inner">
        <p></p>

        <div class="progress">
            <div class="progress-bar" style="width:0%;" data-item="0">0%</div>
        </div>
    </div>
</div>

<div class="wrap" id="at-import-page" data-url="<?php echo admin_url(); ?>" data-import-nonce="<?php echo wp_create_nonce("at_cj_import_wpnonce"); ?>">
	<div class="at-inner">
		<h1><?php _e('AffiliateTheme Import » CJ Affiliate', 'affiliatetheme-cj'); ?></h1>

		<div id="checkConnection"></div>

		<h2 class="nav-tab-wrapper" id="at-api-tabs">
			<a class="nav-tab nav-tab-active" id="settings-tab" href="#top#settings"><?php _e('Einstellungen', 'affiliatetheme-cj'); ?></a>
			<a class="nav-tab" id="search-tab" href="#top#search"><?php _e('Suche', 'affiliatetheme-cj'); ?></a>
            <a class="nav-tab" id="apilog-tab" href="#top#apilog"><?php _e('API Log', 'affiliatetheme-cj'); ?></a>
			<a class="nav-tab" id="buttons-tab" href="#top#buttons"><?php _e('Buttons', 'affiliatetheme-cj'); ?></a>
			<a class="nav-tab" href="https://affiliatetheme.io/forum/foren/support/schnittstellen/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php _e('Hilfe', 'affiliatetheme-cj'); ?></a>
		</h2>
		
		<div class="tabwrapper">
			<!-- START: Settings Tab-->
			<div id="settings" class="at-api-tab active">
				<div id="at-import-settings" class="metabox-holder postbox">
					<h3 class="hndle"><span><?php _e('Einstellungen', 'affiliatetheme-cj'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_options; ?>_form" name="<?php echo $plugin_options; ?>_form">
							<?php settings_fields( $plugin_options ); ?>
							<?php do_settings_sections( $plugin_options ); ?>
							<div class="form-container">
                                <div class="form-group">
                                    <label for="cj_devkey"><?php _e('Developer Key', 'affiliatetheme-cj'); ?> <sup>*</sup></label>
                                    <input type="password" name="cj_devkey" value="<?php echo get_option('cj_devkey'); ?>" />
                                </div>
								<div class="form-group">
									<label for="cj_website_id"><?php _e('Website ID', 'affiliatetheme-cj'); ?><sup>*</sup></label>
									<input type="text" name="cj_website_id" value="<?php echo get_option('cj_website_id'); ?>" />
								</div>
                                <div class="form-group">
                                    <label for="cj_post_status"><?php _e('Produktstatus', 'affiliatetheme-cj'); ?></label>
                                    <?php $selected_cj_post_status = get_option('cj_post_status'); ?>
                                    <select name="cj_post_status" id="cj_post_status">
                                        <option value="publish"><?php _e('Veröffentlicht', 'affiliatetheme-cj'); ?></option>
                                        <option value="draft" <?php if($selected_cj_post_status == "draft") echo 'selected'; ?>><?php _e('Entwurf', 'affiliatetheme-cj'); ?></option>
                                    </select>
                                    <p class="form-hint"><?php _e('Du kannst Produkte sofort veröffentlichen oder als Entwurf anlegen.', 'affiliatetheme-cj'); ?></p>
                                </div>
								<div class="form-group">
									<label for="cj_import_description"><?php _e('Beschreibung', 'affiliatetheme-cj'); ?></label>
									<input type="checkbox" name="cj_import_description" id="cj_import_description" value="1" <?php if('1' == get_option('cj_import_description')) echo 'checked'; ?>> <?php _e('Produktbeschreibung importieren', 'affiliatetheme-cj'); ?>
								</div>
								<h3><?php _e('Einstellungen für den Update-Prozess', 'affiliatetheme-cj'); ?></h3>
								<div class="form-group">
									<label for="cj_update_ean"><?php _e('EAN', 'affiliatetheme-cj'); ?></label>
									<?php $selected_cj_update_ean = get_option('cj_update_ean'); ?>
									<select name="cj_update_ean" id="cj_update_ean">
										<option value="yes" <?php if($selected_cj_update_ean == 'yes' || $selected_cj_update_ean == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-cj'); ?></option>
										<option value="no" <?php if($selected_cj_update_ean == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-cj'); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label for="cj_update_price"><?php _e('Preise', 'affiliatetheme-cj'); ?></label>
									<?php $selected_cj_update_price = get_option('cj_update_price'); ?>
									<select name="cj_update_price" id="cj_update_price">
										<option value="yes" <?php if($selected_cj_update_price == 'yes' || $selected_cj_update_price == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-cj'); ?></option>
										<option value="no" <?php if($selected_cj_update_price == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-cj'); ?></option>
									</select>
								</div>
								<div class="form-group">
									<label for="cj_update_url"><?php _e('URL', 'affiliatetheme-cj'); ?></label>
									<?php $selected_cj_update_url = get_option('cj_update_url'); ?>
									<select name="cj_update_url" id="cj_update_url">
										<option value="yes" <?php if($selected_cj_update_url == 'yes' || $selected_cj_update_url == '') echo 'selected'; ?>><?php _e('Aktualisieren', 'affiliatetheme-cj'); ?></option>
										<option value="no" <?php if($selected_cj_update_url == 'no') echo 'selected'; ?>><?php _e('Nicht aktualisieren', 'affiliatetheme-cj'); ?></option>
									</select>
								</div>
                                <div class="form-group">
                                    <label for="cj_check_product_unique"><?php _e('Produkt überprüfen', 'affiliatetheme-cj'); ?></label>
                                    <?php $selected_cj_check_product_unique = get_option('cj_check_product_unique'); ?>
                                    <select name="cj_check_product_unique" id="cj_check_product_unique">
                                        <option value="no" <?php if($selected_cj_check_product_unique == 'no' || $selected_cj_check_product_unique == '') echo 'selected'; ?>><?php _e('Nicht prüfen', 'affiliatetheme-cj'); ?></option>
                                        <option value="log" <?php if($selected_cj_check_product_unique == 'log') echo 'selected'; ?>><?php _e('Nur in API-Log vermerken', 'affiliatetheme-cj'); ?></option>
                                        <option value="draft" <?php if($selected_cj_check_product_unique == 'draft') echo 'selected'; ?>><?php _e('Produkt als Entwurf setzen', 'affiliatetheme-cj'); ?></option>
                                        <option value="remove" <?php if($selected_cj_check_product_unique == 'remove') echo 'selected'; ?>><?php _e('CJ Affiliate Preis vom Preisvergleich entfernen', 'affiliatetheme-cj'); ?></option>
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
					<h3 class="hndle"><span><?php _e('CJ Affiliate durchsuchen', 'affiliatetheme-cj'); ?></span></h3>
					<div class="inside">
						<div class="form-container">
							<div class="col-left">
								<div class="form-group">
									<label for="search"><?php _e('Suche nach Keyword', 'affiliatetheme-cj'); ?></label>
									<input type="text" name="search" id="search">
								</div>

								<div class="form-group">
									<label for="search_ean"><?php _e('Suche nach EAN', 'affiliatetheme-cj'); ?></label>
									<input type="text" name="search_ean" id="search_ean">
									<p class="form-hint"><?php _e('Bei der EAN Suche sind alle Filter (auf der rechten Seite) wirkungslos.', 'affiliatetheme-cj'); ?></p>
								</div>

								<div class="form-group">
									<label for="cj_shop"><?php _e('Shop', 'affiliatetheme-cj'); ?></label>
									<select name="cj_shop[]" id="cj_shop" multiple="multiple">
										<option>-</option>
									</select>
								</div>

								<div class="form-group" id="category-selector">
									<label><?php _e('Kategorie', 'affiliatetheme-cj'); ?></label>
									<select name="category" id="category">
										<option value="" selected>-</option>
									</select>
								</div>
							</div>

							<div class="col-right">
								<div class="form-group">
									<label for="min_price"><?php _e('Minimaler Preis', 'affiliatetheme-cj'); ?></label>
									<input type="number" name="min_price" id="min_price" step="1" value="" />
								</div>

								<div class="form-group">
									<label for="max_price"><?php _e('Maximaler Preis', 'affiliatetheme-cj'); ?></label>
									<input type="number" name="max_price" id="max_price" step="1" value="" />
								</div>

								<div class="form-group">
									<label for="sort"><?php _e('Sortierung', 'affiliatetheme-cj'); ?></label>
									<select name="sort" id="sort">
                                        <option value=""><?php _e('Relevanz', 'affiliatetheme-cj'); ?></option>
										<option value="price"><?php _e('Preis', 'affiliatetheme-cj'); ?></option>
										<option value="name"><?php _e('Name', 'affiliatetheme-cj'); ?></option>
									</select>
								</div>

								<div class="form-group">
									<label for="order"><?php _e('Reihenfolge', 'affiliatetheme-cj'); ?></label>
									<select name="order" id="order">
										<option value="desc" selected><?php _e('Absteigend', 'affiliatetheme-cj'); ?></option>
										<option value="asc"><?php _e('Aufsteigend', 'affiliatetheme-cj'); ?></option>
									</select>
								</div>

								<div class="form-group">
									<label for="items"><?php _e('Produkte pro Seite', 'affiliatetheme-cj'); ?></label>
									<input type="number" name="items" id="items" step="1" value="25" />
									<p class="form-hint"><?php _e('Maximaler Wert:', 'affiliatetheme-cj'); ?> 500</p>
								</div>
							</div>

							<div class="clearfix"></div>

							<div class="form-group submit-group">
								<input type="hidden" name="page" id="page" value="1">
								<input type="hidden" name="max-pages" id="max-pages" value="">
								<a href="#" id="search-link" class="button button-primary"><?php _e('Suche', 'affiliatetheme-cj'); ?></a>
							</div>
						</div>
						
						<div id="info-title">
							
						</div>
						
						<div class="page-links" style="margin-bottom:15px;">
							<a href="#" class="prev-page button">« <?php _e('Vorherige Seite', 'affiliatetheme-cj'); ?></a>
							<a href="#" class="next-page button"><?php _e('Nächste Seite', 'affiliatetheme-cj'); ?> »</a>
						</div>
										
						<table class="wp-list-table widefat fixed products">
							<thead>
								<tr>
									<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
										<label class="screen-reader-text" for="cb-select-all-1"><?php _e('Alle auswählen', 'affiliatetheme-cj'); ?></label>
										<input id="cb-select-all-1" type="checkbox">
									</th>
									<th scope="col" id="id" class="manage-column column-id" style="width:110px;">
										<span><?php _e('ID', 'affiliatetheme-cj'); ?></span>
									</th>
									<th scope="col" id="productid" class="manage-column column-productid" style="width:110px;">
										<span><?php _e('Artikelnummer', 'affiliatetheme-cj'); ?></span>
									</th>
									<th scope="col" id="image" class="manage-column column-image" style="width:100px;">
										<span><?php _e('Vorschau', 'affiliatetheme-cj'); ?></span>
									</th>
									<th scope="col" id="title" class="manage-column column-title" style="">
										<span><?php _e('Name', 'affiliatetheme-cj'); ?></span>
									</th>
									<th scope="col" id="description" class="manage-column column-shop" style="">
										<span><?php _e('Shop', 'affiliatetheme-cj'); ?></span>
									</th>
									<th scope="col" id="price" class="manage-column column-price" style="">
										<span><?php _e('Preis', 'affiliatetheme-cj'); ?></span>
									</th>
									<th scope="col" id="category" class="manage-column column-category" style="">
										<span><?php _e('Kategorie', 'affiliatetheme-cj'); ?></span>
									</th>
									<th scope="col" id="actions" class="manage-column column-action" style="">
										<span><?php _e('Aktion', 'affiliatetheme-cj'); ?></span>
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
										<a href="#" class="mass-import button button-primary"><?php _e('Ausgewählte Produkte importieren', 'affiliatetheme-cj'); ?></a>
									</td>
								</tr>
							</tfoot>
							<tbody id="results"></tbody>
						</table>

						<div class="page-links" style="margin-top:15px;">
							<a href="#" class="prev-page button">« <?php _e('Vorherige Seite', 'affiliatetheme-cj'); ?></a>
							<a href="#" class="next-page button"><?php _e('Nächste Seite', 'affiliatetheme-cj'); ?> »</a>
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
                    <h3 class="hndle"><span><?php _e('API Log', 'affiliatetheme-cj'); ?></span></h3>
                    <div class="inside">
                        <p><?php _e('Hier werden dir die letzten 200 Einträge der API log angezeigt.', 'affiliatetheme-cj'); ?></p>
                        <p><a href="" class="clear-api-log button" data-type="cj" data-hash="<?php echo CJ_CRON_HASH; ?>"><?php _e('Log löschen', 'affiliatetheme-cj'); ?></a></p>

                        <table class="apilog">
                            <thead>
                            <tr>
                                <th><?php _e('Datum', 'affiliatetheme-cj') ?></th>
                                <th><?php _e('Typ', 'affiliatetheme-cj') ?></th>
                                <th><?php _e('Nachricht', 'affiliatetheme-cj') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $log = get_option('at_cj_api_log');
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
					<h3 class="hndle"><span><?php _e('Buttons', 'affiliatetheme-cj'); ?></span></h3>
					<div class="inside">
						<form action="options.php" method="post" id="<?php echo $plugin_button_options; ?>_form" name="<?php echo $plugin_button_options; ?>_form">
							<?php settings_fields($plugin_button_options); ?>
							<?php do_settings_sections( $plugin_button_options ); ?>
							<p class="hint"><?php _e('Wenn du für CJ Produkte spezielle Button-Texte ausgeben möchtest, kannst du diese hier angeben. <br>Für den Kaufen Button kannst du <u><strong>%s</strong></u> als Platzhalter für den Shopnamen einbinden, sofern angegeben.', 'affiliatetheme-cj'); ?></p>
							<div class="form-container">
								<div class="form-group">
									<label for="cj_buy_short_button"><?php _e('Kaufen Button (kurz)', 'affiliatetheme-cj'); ?></label>
									<input type="text" name="cj_buy_short_button" value="<?php echo (get_option('cj_buy_short_button') ? htmlentities(get_option('cj_buy_short_button')) : __('Kaufen', 'affiliatetheme-cj')); ?>" />
								</div>
								<div class="form-group">
									<label for="cj_buy_button"><?php _e('Kaufen Button', 'affiliatetheme-cj'); ?></label>
									<input type="text" name="cj_buy_button" value="<?php echo (get_option('cj_buy_button') ? htmlentities(get_option('cj_buy_button')) : __('Jetzt bei %s kaufen', 'affiliatetheme-cj')); ?>" />
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