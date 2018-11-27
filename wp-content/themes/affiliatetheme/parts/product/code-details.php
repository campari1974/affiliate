<?php
global $product_details_tax_hide, $product_details_fields_hide, $product_field_groups;
$taxonomies = (($product_details_tax_hide != '1') ? get_product_taxonomies($post->ID, true) : '');
$fields = '';

if($product_details_fields_hide != '1') {
	$product_fields = new AT_Product_Fields($post->ID);
	
	if(!is_singular('product')) {
		// force field groups in shortcode
		if($product_field_groups) {
			$product_fields = new AT_Product_Fields($post->ID, 'detail', $product_field_groups);
		}
	}
	
	$fields = $product_fields->get();
}

if($fields || $taxonomies) { ?>
	<table class="table table-details table-condensed">
		<?php
		/**
		 * Taxonomien
		 */
		if($taxonomies) {
			foreach($taxonomies as $key => $val) {
				echo get_the_term_list($post->ID, $key, '<tr><td>' . $val . '</td><td>', ', ', '</td></tr>');
			}
		}

		/**
		 * Felder
		 */
		if($fields) {
			foreach($fields as $k => $v) {
				$product_field = new AT_Product_Field($v, $post->ID);

				if(!$product_field->is_empty()) {
					$field = $product_field->render();

					echo '<tr>';
					    echo '<td>' . $field['label'] . '</td>';
					    echo '<td>' . $field['value'] . '</td>';
					echo '</tr>';
				}
			}
		}
		?>
	</table>
<?php } ?>