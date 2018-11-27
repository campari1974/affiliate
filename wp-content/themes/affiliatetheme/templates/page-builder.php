<?php 
/*
 * Template Name: Page Builder
 */
get_header();

/*
 * VARS
 */
$sidebar = at_get_sidebar('page', 'builder', $post->ID);
$sidebar_size = at_get_sidebar_size('page', 'builder', $post->ID);
?>

<div id="main" class="<?php echo get_section_layout_class('content'); ?>">
    <?php if($sidebar != 'none') { ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-<?php echo $sidebar_size['content']; ?>">
    <?php } ?>
                    <div id="page-builder" class="<?php echo ($sidebar != 'none' ? 'with-sidebar' : ''); ?>">
                        <div id="content">
                            <?php
                            if( have_rows('page_builder') ):
                                $i = 0;
                                $output = '';

                                while ( have_rows('page_builder') ) : the_row();
                                    $classes = '';

                                    /**
                                     * Feld: Trennlinie
                                     */
                                    if(get_row_layout() == 'page_builder_hr'):
                                        $output .= '<hr>';
                                    endif;

                                    /**
                                     * Feld: Social Buttons
                                     */
                                    if(get_row_layout() == 'page_builder_socialbuttons') :
                                        $attributes = array(
                                            'id' => array(get_sub_field('id')),
                                            'class' => array('section', 'social-buttons', ' item-' . $i),
                                            'style' => array(),
                                        );

                                        if(get_sub_field('class')) {
                                            $attributes['class'][] = get_sub_field('class');
                                        }

                                        if(get_sub_field('id')) {
                                            $attributes['class'][] = 'id-' . get_sub_field('id');
                                        }

                                        $output .= '<div ' . at_attribute_array_html($attributes) . '>';
                                        $output .= ($sidebar != 'none' ? '' : '<div class="container">');
                                        ob_start();
                                        get_template_part('parts/stuff/code', 'social');
                                        $output .= ob_get_contents();
                                        ob_end_clean();
                                        $output .= ($sidebar != 'none' ? '' : '</div>');
                                        $output .= '</div>';
                                    endif;

                                    /**
                                     * Feld: Textarea
                                     */
                                    if(get_row_layout() == 'page_builder_textarea') :
                                        $attributes = array(
                                            'id' => array(get_sub_field('id')),
                                            'class' => array('section', 'textarea', 'textarea-row-' . get_sub_field('rows'), 'item-' . $i),
                                            'style' => array(),
                                        );

                                        if(get_sub_field('class')) {
                                            $attributes['class'][] = get_sub_field('class');
                                        }

                                        if(get_sub_field('padding') == '1') {
                                            $attributes['class'][] = 'no-padding';
                                        }

                                        if(get_sub_field('id')) {
                                            $attributes['class'][] = 'id-' . get_sub_field('id');
                                        }

                                        if(get_sub_field('bgcolor')) {
                                            $attributes['style'][] = 'background-color: ' . get_sub_field('bgcolor') . ';';
                                        }

                                        $output .= '<div ' . at_attribute_array_html($attributes) . '>';
                                        $output .= ($sidebar != 'none' ? '' : '<div class="container">');
                                        if(get_sub_field('rows') == 1) {
                                            $output .= get_sub_field('editor_1');
                                        } else if(get_sub_field('rows') == 2) {
                                            $output .= '<div class="row">';
                                            $output .= '<div class="col-sm-6">';
                                            $output .= get_sub_field('editor_1');
                                            $output .= '</div><div class="col-sm-6">';
                                            $output .= get_sub_field('editor_2');
                                            $output .= '</div>';
                                            $output .= '</div>';
                                        } else if(get_sub_field('rows') == 3) {
                                            $output .= '<div class="row">';
                                            $output .= '<div class="col-sm-4">';
                                            $output .= get_sub_field('editor_1');
                                            $output .= '</div><div class="col-sm-4">';
                                            $output .= get_sub_field('editor_2');
                                            $output .= '</div><div class="col-sm-4">';
                                            $output .= get_sub_field('editor_3');
                                            $output .= '</div>';
                                            $output .= '</div>';
                                        } else if(get_sub_field('rows') == 4) {
                                            $output .= '<div class="row">';
                                            $output .= '<div class="col-sm-3">';
                                            $output .= get_sub_field('editor_1');
                                            $output .= '</div><div class="col-sm-3">';
                                            $output .= get_sub_field('editor_2');
                                            $output .= '</div><div class="col-sm-3">';
                                            $output .= get_sub_field('editor_3');
                                            $output .= '</div><div class="col-sm-3">';
                                            $output .= get_sub_field('editor_4');
                                            $output .= '</div>';
                                            $output .= '</div>';
                                        }
                                        $output .= ($sidebar != 'none' ? '' : '</div>');
                                        $output .= '</div>';
                                    endif;

                                    /**
                                     * Feld: Slideshow
                                     */
                                    if(get_row_layout() == 'page_builder_slideshow') :
                                        global $indicator, $arrows, $interval, $fade, $images;
                                        $indicator = get_sub_field('indicator');
                                        $arrows = get_sub_field('arrows');
                                        $fade = get_sub_field('fade');
                                        $interval = get_sub_field('interval');
                                        $images = get_sub_field('bilder');

                                        $attributes = array(
                                            'id' => array(get_sub_field('id')),
                                            'class' => array('section', 'slideshow', 'item-' . $i),
                                            'style' => array(),
                                        );

                                        if(get_sub_field('class')) {
                                            $attributes['class'][] = get_sub_field('class');
                                        }

                                        if(get_sub_field('id')) {
                                            $attributes['class'][] = 'id-' . get_sub_field('id');
                                        }

                                        if($images) {
                                            $output .= '<div ' . at_attribute_array_html($attributes) . '>';
                                            ob_start();
                                            get_template_part('parts/teaser/code', 'teaser');
                                            $output .= ob_get_contents();
                                            ob_end_clean();
                                            $output .= '</div>';
                                        }
                                    endif;

                                    /**
                                     * Feld: AT - Testimonials
                                     */
                                    if(get_row_layout() == 'page_builder_testimonials') :
                                        $text = get_sub_field('text');
                                        $fields = get_sub_field('testimonials');

                                        $attributes = array(
                                            'id' => array(get_sub_field('id')),
                                            'class' => array('section', 'testimonials', 'item-' . $i),
                                            'style' => array(),
                                        );

                                        if(get_sub_field('id')) {
                                            $attributes['class'][] = 'id-' . get_sub_field('id');
                                        }

                                        if(get_sub_field('class')) {
                                            $attributes['class'][] = get_sub_field('class');
                                        }

                                        $output .= '<div ' . at_attribute_array_html($attributes) . '>';
                                        $output .= ($sidebar != 'none' ? '' : '<div class="container">');
                                        if($text) {
                                            $output .= '<div class="inner">' . $text . '</div>';
                                        }

                                        if($fields) {
                                            $output .= '<div class="row">';
                                            foreach($fields as $field) {
                                                $image = $field['image'];
                                                $output .= '<div class="col-md-4 col-sm-6 col-xs-6 col-xxs-12">';
                                                $output .= '<blockquote>';
                                                $output .= $field['text'];
                                                $output .= '<footer>';
                                                if($image) {
                                                    $output .= '<img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $image['alt'] . '" class="alignleft" />';
                                                }

                                                if($field['name']) {
                                                    $output .= '<strong>' . $field['name'] . '</strong>';
                                                }

                                                if($field['web']) {
                                                    $output .= '<cite title="' . $field['name'] . '">';
                                                    $output .= '<a href="' . $field['web'] . '" target="_blank" rel="nofollow">' . at_clean_url($field['web']) . '</a>';
                                                    $output .= '</cite>';
                                                }
                                                $output .= '</footer>';
                                                $output .= '</blockquote>';
                                                $output .= '</div>';
                                            }
                                            $output .= '</div>';
                                        }
                                        $output .= ($sidebar != 'none' ? '' : '</div>');
                                        $output .= '</div>';
                                    endif;

                                    /**
                                     * Feld: AT - Filter
                                     */
                                    if( get_row_layout() == 'page_builder_filter' ):
                                        $headline = get_sub_field('headline');

                                        $attributes = array(
                                            'id' => array(get_sub_field('id')),
                                            'class' => array('section', 'filter', 'text-center', 'item-' . $i),
                                            'style' => array(),
                                        );

                                        if(get_sub_field('class')) {
                                            $attributes['class'][] = get_sub_field('class');
                                        }

                                        if(get_sub_field('id')) {
                                            $attributes['class'][] = 'id-' . get_sub_field('id');
                                        }

                                        if(get_sub_field('bgcolor')) {
                                            $attributes['style'][] = 'background-color: ' . get_sub_field('bgcolor') . ';';
                                        }

                                        if(get_sub_field('bgimage')) {
                                            $bgimage = get_sub_field('bgimage');
                                            $attributes['style'][] = 'background-image: url(' . $bgimage['url'] . ');';
                                        }

                                        $output .= '<div ' . at_attribute_array_html($attributes) . '>';
                                        $output .= ($sidebar != 'none' ? '' : '<div class="container">');
                                        if($headline) {
                                            $output .= '<p class="h1">' . $headline . '</p>';
                                        }

                                        $filter_page_args = array(
                                            'post_type' => 'page',
                                            'meta_key' => '_wp_page_template',
                                            'meta_value' => 'templates/filter.php',
                                            'posts_per_page' => 1
                                        );
                                        $filter_page = get_posts($filter_page_args);
                                        if($filter_page) {
                                            $output .= '<form action="' . get_permalink($filter_page[0]->ID) . '" method="GET" class="filterform form-inline">';

                                            /**
                                             * Field Type: Taxonomy
                                             * @last_updated: 12/07/2016
                                             */
                                            $taxonomies = get_sub_field('taxonomies');
                                            if($taxonomies) {
                                                $product_tax = get_field('product_tax', 'option');
                                                foreach($taxonomies as $tax) {
                                                    if(!$tax) {
                                                        break;
                                                    }

                                                    $terms = get_terms($tax->name, 'hide_empty=1');
                                                    $product_tax_row = getRepeaterRowID($product_tax, 'name', $tax->labels->name);
                                                    $instructions = (isset($product_tax[$product_tax_row]['instructions']) ? $product_tax[$product_tax_row]['instructions'] : '');
                                                    $first_label = apply_filters('at_set_filter_taxonomy_first_label', sprintf(__('%s wählen', 'affiliatetheme'), $tax->labels->name), $tax);

                                                    if($terms) {
                                                        $output .= '<div class="form-group">';
                                                        $output .= '<label for="' . $tax->name . '" class="control-label">' . $tax->labels->name . '</label>';
                                                        $output .= '<select id="' . $tax->name . '" name="' . $tax->name . '" class="form-control">';
                                                        $output .= '<option value="">' . $first_label . '</option>';
                                                        $output .= at_get_terms_hierarchical($terms, '', 0, 0, (isset($_GET[$tax->name]) ? $_GET[$tax->name] : ''));
                                                        $output .= '</select>';
                                                        $output .= ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : '');
                                                        $output .= '</div>';
                                                    }
                                                }
                                            }

                                            /**
                                             * Field Type: Price
                                             * @last_updated: 12/07/2016
                                             */
                                            $price = get_sub_field('price');
                                            if($price) {
                                                $name = 'price';
                                                $values = at_field_database_min_max_value($name, 'product');
                                                $steps = at_field_step_value($values->min, $values->max, '', $name);
                                                $value = at_clean_data((isset($_GET[$name]) ? $_GET[$name] : ''));
                                                $instructions = apply_filters('at_set_filter_price_instructions', '');

                                                $output .= '<div class="form-group">';
                                                $output .= '<label for="' . $name . '" class="control-label">' . apply_filters('at_filter_price_title', __('Preis', 'affiliatetheme')) . '</label>';
                                                $output .= '<div class="slide">';
                                                $output .= '<span>' . $values->min . '</span>';
                                                $output .= '<input id="' . $name . '" name="' . $name . '" data-slider-label="' . apply_filters('at_filter_price_label', ' ' . at_get_default_currency(true)) . '" type="text" class="bt-slider" value=" ' . ($value ? $value : ''). '" data-slider-min="' . $values->min . '" data-slider-max="' . $values->max . '" data-slider-step="' . $steps . '" data-slider-value="[' . ($value ? $value : $values->min . ',' . $values->max) . ']">';
                                                $output .= '<span>' . $values->max . '</span>';
                                                $output .= '</div>';
                                                $output .= '<div class="clearfix"></div>';
                                                $output .= ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : '');
                                                $output .= '</div>';
                                            }

                                            /**
                                             * Field Type: Rating
                                             * @last_updated: 12/07/2016
                                             */
                                            $rating = get_sub_field('rating');
                                            if($rating) {
                                                $name = 'product_rating';
                                                $values = (object) array('min' => 0, 'max' => 5);
                                                $steps = at_field_step_value($values->min, $values->max, '', $name);
                                                $value = at_clean_data((isset($_GET[$name]) ? $_GET[$name] : ''));
                                                $instructions = apply_filters('at_set_filter_rating_instructions', '');

                                                $output .= '<div class="form-group">';
                                                $output .= '<label for="' . $name . '" class="control-label">' . apply_filters('at_filter_rating_title', __('Bewertung', 'affiliatetheme')) . '</label>';
                                                $output .= '<div class="slide">';
                                                $output .= '<span>' . $values->min . '</span>';
                                                $output .= '<input id="' . $name . '" name="' . $name . '" data-slider-label="' . apply_filters('at_filter_rating_label', ' ' . __('Sterne', 'affiliatetheme')) . '" type="text" class="bt-slider" value="' . ($value ? $value : '') . '" data-slider-min="' . $values->min . '" data-slider-max="' . $values->max . '" data-slider-step="' . $steps . '" data-slider-value="[' . ($value ? $value : $values->min . ',' . $values->max) . ']">';
                                                $output .= '<span>' . $values->max . '</span>';
                                                $output .= '</div>';
                                                $output .= '<div class="clearfix"></div>';
                                                $output .= ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : '');
                                                $output .= '</div>';
                                            }

                                            /**
                                             * Field Type: Custom Fields
                                             * @last_updated: 12/07/2016
                                             */
                                            $customfields = get_sub_field('customfields');
                                            if($customfields) {
                                                // filter duplicate fields
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

                                                        $output .= '<div class="form-group">';
                                                        $output .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
                                                        $output .= '<div class="slide">';
                                                        $output .= '<span>' . $values->min . '</span>';
                                                        $output .= '<input id="' . $name . '" name="' . $name . '" data-slider-label="' . apply_filters('at_filter_customfield_label', $append, $field_obj) . '" type="text" class="bt-slider" value="' . ($value ? $value : '') . '" data-slider-min="' . $values->min . '" data-slider-max="' . $values->max . '" data-slider-step="' . $steps . '" data-slider-value="[' . ($value ? $value : $values->min . ',' . $values->max) . ']">';
                                                        $output .= '<span>' . $values->max . '</span>';
                                                        $output .= '</div>';
                                                        $output .= '<div class="clearfix"></div>';
                                                        $output .= ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : '');
                                                        $output .= '</div>';
                                                    } else if('true_false' == $field_obj['type']) {
                                                        $output .= '<div class="form-group">';
                                                        $output .= '<div class="checkbox">';
                                                        $output .= '<label for="' . $name . '">';
                                                        $output .= '<input type="checkbox" value="1" name="' . $name . '" id="' . $name . '" ' . ($value == '1' ? 'checked' : '') . ' /> ' . $label;
                                                        $output .= '</label>';
                                                        $output .= '</div>';
                                                        $output .= ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : '');
                                                        $output .= '</div>';
                                                    } else if('select' == $field_obj['type']) {
                                                        $choices = $field_obj['choices'];
                                                        $first_label = apply_filters('at_set_filter_customfield_first_label', '-', $name, $label);

                                                        $output .= '<div class="form-group">';
                                                        $output .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
                                                        $output .= '<select name="' . $name . '" class="form-control">';
                                                        $output .= '<option value="" selected>' . $first_label . '</option>';
                                                        foreach($choices as $k => $v) {
                                                            $output .= '<option value="' . $k . '" ' . ($k == $value ? 'selected' : '') . '>' . $v . '</option>';
                                                        }
                                                        $output .= '</select>';
                                                        $output .= ($instructions ? '<span class="filter-instruction">' . $instructions . '</span>' : '');
                                                        $output .= '</div>';
                                                    }
                                                }
                                            }

                                            $output .= '<div class="form-group form-group-block">';
                                            $output .= '<button type="submit" class="btn btn-at">' . apply_filters('at_set_filter_search_button_label', __('Filtern', 'affiliatetheme')) . '</button>';
                                            $output .= '</div>';
                                            $output .= '<div class="clearfix"></div>';
                                            $output .= '</form>';
                                        } else {
                                            if (is_user_logged_in()) {
                                                $output .= '<div class="alert alert-info"><p>' . __('<strong>Hinweis:</strong> Bitte lege eine Seite mit dem Template "Filter" fest, damit der Filter korrekt funktionieren kann.', 'affiliatetheme-backend') . '</p></div>';
                                            }
                                        }
                                        $output .= ($sidebar != 'none' ? '' : '</div>');
                                        $output .= '</div>';
                                    endif;

                                    /**
                                     * Feld: AT - Filter v2
                                     */
                                    if( get_row_layout() == 'page_builder_product_filter' ):
                                        $headline = get_sub_field('headline');

                                        $attributes = array(
                                            'id' => array(get_sub_field('id')),
                                            'class' => array('section', 'filter', 'item-' . $i),
                                            'style' => array(),
                                        );

                                        if(get_sub_field('class')) {
                                            $attributes['class'][] = get_sub_field('class');
                                        }

                                        if(get_sub_field('id')) {
                                            $attributes['class'][] = 'id-' . get_sub_field('id');
                                        }

                                        if(get_sub_field('bgcolor')) {
                                            $attributes['style'][] = 'background-color: ' . get_sub_field('bgcolor') . ';';
                                        }

                                        if(get_sub_field('bgimage')) {
                                            $bgimage = get_sub_field('bgimage');
                                            $attributes['style'][] = 'background-image: url(' . $bgimage['url'] . ');';
                                        }

                                        $output .= '<div ' . at_attribute_array_html($attributes) . '>';
                                        $output .= ($sidebar != 'none' ? '' : '<div class="container">');
                                        if($headline) {
                                            $output .= '<p class="h1">' . $headline . '</p>';
                                        }

                                        $filter_id = get_sub_field('filter');

                                        if($filter_id) {
                                            $output .= do_shortcode('[at_filter id="' . $filter_id . '" /]');
                                        }

                                        $output .= ($sidebar != 'none' ? '' : '</div>');
                                        $output .= '</div>';
                                    endif;

                                    $i++;
                                endwhile;

                                remove_filter('the_content', 'wpautop');
                                echo apply_filters('the_content', $output);
                                add_filter('the_content', 'wpautop');
                            endif;
                            ?>

                            <?php
                            if(comments_open()) {
                                if($sidebar == 'none') {
                                    echo '<div class="container">';
                                }
                                get_template_part('parts/stuff/code', 'comments');
                                if($sidebar == 'none') {
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>

        <?php if($sidebar != 'none') { ?>
                </div>

        <?php if('left' == $sidebar || 'right' == $sidebar) { ?>
            <div class="col-sm-<?php echo $sidebar_size['sidebar']; ?>">
                <div id="sidebar">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
    <?php }  ?>
</div>

<?php get_footer(); ?>