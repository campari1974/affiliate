<?php
/**
 * Widget: Filter
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	widgets
 */

class filter_widget extends WP_Widget {
	public function __construct() { 
		$widget_ops = array('classname' => 'widget_filter', 'description' => __('Dieses Widget zeigt den alten Produktfilter an. Wenn Möglich dieses Element nicht mehr nutzen, sondern die neue Version des Filters. Der alte Filter wird in einer späteren Version entfernt.', 'affiliatetheme-backend'));
		parent::__construct('filter_widget', __('affiliatetheme.io &raquo; Filter (ALT)', 'affiliatetheme-backend'), $widget_ops);
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		global $post; 
		
		/*
		 * VARS
		 */
        $price = $instance['price'];
		$price_title = (isset($instance['price_title']) ? $instance['price_title'] : __('Preis', 'affiliatetheme'));
        $rating = $instance['rating'];
		$rating_title = (isset($instance['rating_title']) ? $instance['rating_title'] : __('Bewertung', 'affiliatetheme'));
		$taxs = $instance['taxonomies'];
		$customfields = get_field('customfields', 'widget_' . $args['widget_id']);
		
		echo $before_widget;
		
		if($instance['title']) { echo $before_title . $instance['title'] . $after_title; }

		if($filter_page = get_posts(array('post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'templates/filter.php', 'posts_per_page' => 1))) {
			?>
			<form action="<?php echo get_permalink($filter_page[0]->ID); ?>" method="GET" class="filterform">
			<?php
                if($price) {
                    $name = 'price';
                    $values = at_field_database_min_max_value($name, 'product');
                    $steps = at_field_step_value($values->min, $values->max, '', $name);
                    $value = at_clean_data((isset($_GET[$name]) ? $_GET[$name] : ''));
					$instructions = apply_filters('at_set_filter_price_instructions', '');
                    ?>
                    <div class="form-group">
                        <label for="<?php echo $name; ?>" class="control-label"><?php echo $price_title; ?></label>
                        <div class="slide">
                            <span><?php echo $values->min; ?></span>
                            <input id="<?php echo $name; ?>" name="<?php echo $name; ?>" data-slider-label="<?php echo apply_filters('at_filter_price_label', ' ' . at_get_default_currency(true)); ?>" type="text" class="bt-slider" value="<?php if($value) { echo $value; } ?>" data-slider-min="<?php echo $values->min; ?>" data-slider-max="<?php echo $values->max; ?>" data-slider-step="<?php echo $steps; ?>" data-slider-value="[<?php if($value) { echo $value; } else { echo $values->min . ',' . $values->max; }?>]" >
                            <span><?php echo $values->max; ?></span>
                        </div>
						<div class="clearfix"></div>
						<?php echo ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : ''); ?>
                    </div>
                <?php
                }

                if($rating) {
                    $name = 'product_rating';
					$values = (object) array('min' => 0, 'max' => 5);
                    $steps = at_field_step_value($values->min, $values->max, '', $name);
                    $value = at_clean_data((isset($_GET[$name]) ? $_GET[$name] : ''));
					$instructions = apply_filters('at_set_filter_rating_instructions', '');
                    ?>
                    <div class="form-group">
                        <label for="<?php echo $name; ?>" class="control-label"><?php echo $rating_title; ?></label>
                        <div class="slide">
                            <span><?php echo $values->min; ?></span>
                            <input id="<?php echo $name; ?>" name="<?php echo $name; ?>" data-slider-label="<?php echo apply_filters('at_filter_rating_label', ' Sterne'); ?>" type="text" class="bt-slider" value="<?php if($value) { echo $value; } ?>" data-slider-min="<?php echo $values->min; ?>" data-slider-max="<?php echo $values->max; ?>" data-slider-step="<?php echo $steps; ?>" data-slider-value="[<?php if($value) { echo $value; } else { echo $values->min . ',' . $values->max; }?>]" >
                            <span><?php echo $values->max; ?></span>
                        </div>
						<div class="clearfix"></div>
						<?php echo ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : ''); ?>
                    </div>
                <?php
                }

				if($customfields) {
                    if(!is_array($customfields)) {
                        $customfields = json_decode($customfields);
                    }
					$customfields = array_unique($customfields);
					foreach($customfields as $field) {
						$field_obj = get_field_object($field);
						$key = $field_obj['key'];
						$name = $field_obj['name'];
						$label = $field_obj['label'];
						$value = at_clean_data((isset($_GET[$name]) ? $_GET[$name] : ''));
						$instructions = $field_obj['instructions'];

						if('number' == $field_obj['type']) {
							$append = $field_obj['append'];
							$values = at_field_min_max_value($key, $name, 'product');
                            $steps = at_field_step_value($values->min, $values->max, $key, $name);
							?>
							<div class="form-group">
								<label for="<?php echo $name; ?>" class="control-label"><?php echo $label; ?></label>
								<div class="slide">
									<span><?php echo $values->min; ?></span>
									<input id="<?php echo $name; ?>" name="<?php echo $name; ?>" data-slider-label="<?php echo apply_filters('at_filter_customfield_label', $append, $field_obj); ?>" type="text" class="bt-slider" value="<?php if($value) { echo $value; } ?>" data-slider-min="<?php echo $values->min; ?>" data-slider-max="<?php echo $values->max; ?>" data-slider-step="<?php echo $steps; ?>" data-slider-value="[<?php if($value) { echo $value; } else { echo $values->min . ',' . $values->max; }?>]" >
									<span><?php echo $values->max; ?></span>
								</div>
								<div class="clearfix"></div>
								<?php echo ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : ''); ?>
							</div>
							<?php
						} else if('true_false' == $field_obj['type']) {
							?>
							<div class="form-group">
								<div class="checkbox">
									<label for="<?php echo $name; ?>">
										<input type="checkbox" value="1" name="<?php echo $name; ?>" <?php if($value == '1') echo 'checked'; ?>> <?php echo $label; ?>
									</label>
								</div>
								<?php echo ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : ''); ?>
							</div>
							<?php
						} else if('select' == $field_obj['type']) {
                            $choices = $field_obj['choices'];
							$first_label = apply_filters('at_set_filter_customfield_first_label', '-', $name, $label);
                            ?>
                            <div class="form-group">
                                <label for="<?php echo $name; ?>" class="control-label"><?php echo $label; ?></label>
                                <select name="<?php echo $name; ?>" class="form-control">
									<option value="" selected><?php echo $first_label; ?></option>
									<?php foreach($choices as $k => $v) { ?>
                                        <option value="<?php echo $k; ?>" <?php if($k == $value) echo 'selected'; ?>><?php echo $v; ?></option>
                                    <?php } ?>
                                </select>
								<?php echo ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : ''); ?>
                            </div>
                            <?php
						}
					}
				}

				if($taxs) {
					$product_tax = get_field('product_tax', 'option');
					foreach($taxs as $tax) {
						$tax_obj = get_taxonomy($tax);
						$terms = get_terms($tax, 'hide_empty=1');
						$product_tax_row = getRepeaterRowID($product_tax, 'name', $tax_obj->labels->name);
						$instructions = (isset($product_tax[$product_tax_row]['instructions']) ? $product_tax[$product_tax_row]['instructions'] : '');
						$first_label = apply_filters('at_set_filter_taxonomy_first_label', sprintf(__('%s wählen', 'affiliatetheme'), $tax_obj->labels->name), $tax_obj);
						
						if($terms) {
							?>
							<div class="form-group">
								<label for="<?php echo $tax_obj->name; ?>" class="control-label"><?php echo $tax_obj->labels->name; ?></label>
								<select id="<?php echo $tax_obj->name; ?>" name="<?php echo $tax_obj->name; ?>" class="form-control">
									<option value=""><?php echo $first_label; ?></option>
									<?php echo at_get_terms_hierarchical($terms, '', 0, 0, $_GET[$tax_obj->name]); ?>
								</select>
								<?php echo ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : ''); ?>
							</div>
						<?php
						}
					}
				}
				?>
				
				<div class="form-group">
                    <a href="<?php echo parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH); ?>" class="btn btn-xs btn-link filter-reset pull-right"><?php _e('Filter zurücksetzen', 'affiliatetheme'); ?></a>
					<button type="submit" class="btn btn-at"><?php echo apply_filters('at_set_filter_search_button_label', __('Filtern', 'affiliatetheme')); ?></button>
				</div>
			</form>
			<?php
		} else {
			if(is_user_logged_in()) {
				echo '<div class="textwidget"><p>' . __('<strong>Hinweis:</strong> Bitte lege eine Seite mit dem Template "Filter" fest, damit der Filter korrekt funktionieren kann.', 'affiliatetheme-backend') . '</p></div>';
			}
		}

		echo $after_widget;
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['price'] = $new_instance['price'];
		$instance['price_title'] = $new_instance['price_title'];
        $instance['rating'] = $new_instance['rating'];
		$instance['rating_title'] = $new_instance['rating_title'];
		$instance['taxonomies'] = $new_instance['taxonomies'];
		
		return $instance;
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array('title' => '', 'price' => '', 'price_title' => '', 'rating' => '', 'rating_title' => '', 'taxonomies' => '') );
		$title = $instance['title'];
        $price = $instance['price'];
		$price_title = $instance['price_title'];
        $rating = $instance['rating'];
		$rating_title = $instance['rating_title'];
		$taxonomies = ($instance['taxonomies'] ? $instance['taxonomies'] : array());
		$taxonomies_obj = get_object_taxonomies('product', 'objects');
		?>
		
		<div class="hint">
			<p>
				<?php _e('Wenn du den Filter nutzen willst, musst du anschließend eine Zielseite erstellen. Lege dazu einfach eine neue Seite an und definiere das Template "Filter". Anschließend leitet der Filter automatisch auf diese Seite weiter.', 'affiliatetheme-backend'); ?>
			</p>
			<p>
				<?php _e('Bitte wählen hier aus nach welchen Werten gefiltert werden soll. Für alle Werte die eine Nummer beinhalten (z.B. Preis) wird ein "Slider" im Filter angezeigt.', 'affiliatetheme-backend'); ?>
			</p>
		</div>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:', 'affiliatetheme-backend'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>">
		</p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['price'], 'on'); ?> id="<?php echo $this->get_field_id('price'); ?>" name="<?php echo $this->get_field_name('price'); ?>" />
            <label for="<?php echo $this->get_field_id('price'); ?>"><?php _e('Preis', 'affiliatetheme-backend'); ?></label>
        </p>

		<p>
			<label for="<?php echo $this->get_field_id('price_title'); ?>"><?php _e('Überschrift: Preis:', 'affiliatetheme-backend'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('price_title'); ?>" name="<?php echo $this->get_field_name('price_title'); ?>" value="<?php echo ($price_title ? $price_title : __('Preis', 'affiliatetheme')); ?>">
		</p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['rating'], 'on'); ?> id="<?php echo $this->get_field_id('rating'); ?>" name="<?php echo $this->get_field_name('rating'); ?>" />
            <label for="<?php echo $this->get_field_id('rating'); ?>"><?php _e('Bewertung', 'affiliatetheme-backend'); ?></label>
        </p>

		<p>
			<label for="<?php echo $this->get_field_id('rating_title'); ?>"><?php _e('Überschrift: Bewertung:', 'affiliatetheme-backend'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('rating_title'); ?>" name="<?php echo $this->get_field_name('rating_title'); ?>" value="<?php echo ($rating_title ? $rating_title : __('Bewertung', 'affiliatetheme')); ?>">
		</p>
		
		<?php if($taxonomies_obj) { ?>
			<p>
				<label for="<?php echo $this->get_field_id('taxonomies'); ?>"><?php _e('Taxonomien:', 'affiliatetheme-backend'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('taxonomies'); ?>" name="<?php echo $this->get_field_name('taxonomies'); ?>[]" multiple>
					<?php foreach($taxonomies_obj as $tax) { ?>
						<option value="<?php echo $tax->name; ?>" <?php if(in_array($tax->name, $taxonomies)) echo 'selected'; ?>><?php echo $tax->labels->name; ?></option>
					<?php } ?>
				</select>
			</p>
		<?php } ?>
		
		<?php
		}
}

add_action( 'widgets_init', 'at_filter_widget' );
function at_filter_widget() {
	register_widget('filter_widget');
}