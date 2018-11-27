<?php
/**
 * Depcrecated functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	product
 */

if ( ! function_exists( 'get_currency_sym' ) ) {
    /**
     * get_currency_sym function.
     * @deprecated since 1.3.7
     */
    function get_currency_sym($currency) {
        return at_get_currency_sym($currency);
    }
}

if ( ! function_exists( 'get_product_field_objects' ) ) {
    /**
     * get_product_field_objects function.
     *
     * @param  int $post_id
     * @param  string $pos (default: detail)
     * @param  array $group_fields
     * @return string

     * 凸(¬‿¬)凸
     * @success http://bit.ly/1KitBxl
     *
     * @deprecated since 1.5.1
     */
    function get_product_field_objects($post_id = '', $pos = 'detail', $group_fields = array()) {
        $output = array();

        if(empty($group_fields)) {
            $group_fields_tmp = array();
            $group_fields = array();
            $acf_field_groups = at_get_acf_field_groups();
            foreach($acf_field_groups as $acf_field_group) {
                foreach($acf_field_group['location'] as $group_locations) {
                    $matches = array();

                    $rule_posttype = getRepeaterRowID($group_locations, 'value', 'product');
                    $rule_pos = getRepeaterRowID($group_locations, 'value', $pos);

                    /*
                     * @depcreated
                     */
                    $core_fields = array(
                        'group_555af959627b0',
                        'group_552a53b6bcc37',
                        'group_552bdb878e1a9',
                        'group_552bd329a6457',
                        'group_553b6f6e9891b',
                        'group_5582afd4210dc',
                        'group_554b94039138a',
                        'group_553b825363a7d',
                        'group_555b2395904ef',
                        'group_558c1d86807b0',
                        'group_552fbd209f99e',
                        'group_559279aced3ba',
                        'group_5555f16e6add6',
                        'group_5559aa9c154f9'
                    );

                    if(isset($acf_field_group['key']) && in_array($acf_field_group['key'], $core_fields)) {
                        continue;
                    }

                    if(is_int($rule_posttype)) {
                        if(isset($group_locations[$rule_posttype]['operator']) && $group_locations[$rule_posttype]['operator'] != '==') {
                            continue;
                        }
                        if(is_int($rule_pos)) {
                            if(isset($group_locations[$rule_pos]['operator']) && $group_locations[$rule_pos]['operator'] != '==') {
                                continue;
                            }
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }

                    foreach($group_locations as $rule) {
                        if ($rule['param'] == 'post_taxonomy') {
                            $tax_term = explode(':', $rule['value']);
                            if ($tax_term) {
                                if ($rule['operator'] == '==') {
                                    if (taxonomy_exists($tax_term[0])) {
                                        if (has_term($tax_term[1], $tax_term[0], $post_id)) {
                                            $matches[] = 'true';
                                        } else {
                                            $matches[] = 'false';
                                        }
                                    } else {
                                        $matches[] = 'false';
                                    }
                                }

                                if ($rule['operator'] == '!=') {
                                    if (taxonomy_exists($tax_term[0])) {
                                        if (has_term($tax_term[1], $tax_term[0], $post_id)) {
                                            $matches[] = 'false';
                                        } else {
                                            $matches[] = 'true';
                                        }
                                    } else {
                                        $matches[] = 'false';
                                    }
                                }
                            }
                        }
                    }

                    if(!in_array('false', $matches)) {
                        if(is_array($group_fields) && is_array(acf_get_fields($acf_field_group))) {
                            $group_fields = array_merge($group_fields, acf_get_fields($acf_field_group));
                        }
                    }
                }
            }
        }

        if($group_fields) {
            if("" != $post_id) {
                /**
                 * Felder für Produkt Filtern!
                 */
                $post_fields = get_field_objects($post_id);
                $product_misc_table_empty_field = (get_field('product_misc_table_empty_field', 'option') ? get_field('product_misc_table_empty_field', 'option') : '-');
                if($group_fields && $post_fields) {
                    foreach($group_fields as $key => $val) {
                        $field_val = get_field($group_fields[$key]['name'], $post_id);
                        if($field_val) {
                            $group_fields[$key]['value'] = $field_val;
                        } else {
                            $group_fields[$key]['value'] = $product_misc_table_empty_field;
                        }
                    }
                }
            }

            $output = $group_fields;
            return apply_filters('at_before_render_product_fields_table', $output, $post_id, $pos);
        }
    }
}

if ( ! function_exists( 'render_product_fields' ) ) {
    /**
     * render_product_fields function.
     *
     * @param  int $post_id
     * @param  string $pos (default: detail)
     * @param  array $group_fields
     * @param  array $show_empty
     * @return string
     *
     * @deprecated since 1.5.1
     */
    function render_product_fields($post_id, $pos, $group_fields = array(), $show_empty = false) {
        $fields = get_product_field_objects($post_id, $pos, $group_fields);
        $output = array();

        if($fields) {
            foreach($fields as $field) {
                $field = apply_filters('at_before_render_product_fields', $field, $post_id, $pos);

                if('text' == $field['type'] || 'number' == $field['type'] || 'email' == $field['type']) {

                    $value = $field['value'];

                    if($value) {
                        if($field['prepend']) $value = $field['prepend'] . $value;
                        if($field['append']) $value .= $field['append'];

                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $value;
                    }

                } else if('select' == $field['type']) {

                    if($field['value']) {

                        if(is_array($field['value'])) {
                            $value_label_arr = array();
                            foreach($field['value'] as $val) {
                                $value_label_arr[] = (isset($field['choices'][$val]) ? $field['choices'][$val] : $val);
                            }

                            if($value_label_arr) {
                                $value_label = implode(', ', $value_label_arr);
                            } else {
                                $value_label = '';
                            }
                        } else {
                            $value_label = (isset($field['choices'][$field['value']]) ? $field['choices'][$field['value']] : $field['value']);
                        }

                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $value_label;
                    }

                } else if('url' == $field['type']) {

                    $value = '';
                    $value = '<a href="'.$field['value'].'" target="_blank" rel="nofollow">'.$field['value'].'</a>';

                    if($value) {
                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $value;
                    }

                } else if('image' == $field['type']) {

                    $image = $field['value'];
                    $value = '';

                    if($image['url'] && $image != '-') {
                        $value = '<img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $image['alt'] . '" class="img-responsive" />';
                    }

                    if($value) {
                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $value;
                    }

                } else if('file' == $field['type']) {

                    $file = $field['value'];
                    $value = '';
                    $value = '<a href="'.$file['url'].'" target="_blank">'.$file['title'].'</a>';

                    if($value) {
                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $value;
                    }

                } else if('true_false' == $field['type']) {

                    $value = '';
                    $product_misc_truefalse_true = get_field('product_misc_truefalse_true', 'option');
                    $product_misc_truefalse_false = get_field('product_misc_truefalse_false', 'option');

                    if('1' == $field['value']) {
                        $value = apply_filters('at_product_fields_true_value', $product_misc_truefalse_true, $post_id);
                    } else {
                        $value =  apply_filters('at_product_fields_false_value', $product_misc_truefalse_false, $post_id);
                    }

                    if($value) {
                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $value;
                    }

                } else if('post_object' == $field['type']) {

                    $item = $field['value'];
                    if($item) {
                        $value = '<a href="' . get_permalink($item) . '" title="' . get_the_title($item) . '">' . get_the_title($item) . '</a>';

                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $value;
                    }

                } else if('relationship' == $field['type']) {

                    $links = array();
                    $items = $field['value'];

                    if(is_array($items)) {
                        foreach($items as $item) {
                            $links[] = '<a href="' . get_permalink($item->ID) . '" title="' . get_the_title($item->ID) . '">' . get_the_title($item->ID) . '</a>';
                        }

                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = (is_array($links) ? implode(', ',$links) : $links);
                    }

                } else if('taxonomy' == $field['type']) {

                    $links = array();
                    $terms = ($field['value'] ? $field['value'] : '');
                    $taxonomy = $field['taxonomy'];

                    if($terms) {
                        if(is_array($terms)) {
                            foreach ($terms as $term) {
                                $term_obj = get_term_by('id', $term, $taxonomy);
                                $term_link = get_term_link($term, $taxonomy);

                                if($term_obj) {
                                    $links[] = '<a href="' . $term_link . '" title="' . $term_obj->name . '">' . $term_obj->name . '</a>';
                                }
                            }
                        } else {
                            $term_obj = get_term_by('id', $terms, $taxonomy);
                            $term_link = get_term_link($terms, $taxonomy);

                            if($term_obj) {
                                $links[] = '<a href="' . $term_link . '" title="' . $term_obj->name . '">' . $term_obj->name . '</a>';
                            }
                        }

                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = (is_array($links) ? implode(', ',$links) : $links);
                    }

                } else if('user' == $field['type']) {

                    $value = '';

                    $user = $field['value'];
                    if($user) {
                        $value = '<a href="'.get_author_posts_url($user['ID']).'" title="'.$user['display_name'].'">'.$user['display_name'].'</a>';

                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $value;
                    }

                } else if('checkbox' == $field['type']) {

                    $choices = $field['value'];
                    if($choices) {
                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = (is_array($choices) ? implode(', ', $choices) : $choices);
                    }

                } else if('gallery' == $field['type']) {

                    $image_ids = array();
                    $images = $field['value'];
                    if($images) {
                        foreach($images as $image) {
                            $image_ids[] = $image['ID'];
                        }

                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = do_shortcode('[gallery ids="'.implode(',',$image_ids).'" link="file"]');
                    }

                } else if('color_picker' == $field['type']) {

                    $color = $field['value'];
                    if($color) {
                        $text = '<span class="color_picker-holder" style="background:'.$color.'"></span> <span class="color_picker-text">'.$color.'</span>';

                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $text;
                    }

                } else {

                    if($field['value']) {
                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = $field['value'];
                    }
                }

                if(true == $show_empty) {

                    if(!isset($output[$field['name']]['value']) || !$output[$field['name']]['value']) {
                        $output[$field['name']]['label'] = $field['label'];
                        $output[$field['name']]['value'] = '';
                    }

                }
            }

            return apply_filters('at_render_product_fields', $output, $post_id, $pos);
        }
    }
}