<?php
if(get_comments_number($post->ID) == 0) { echo '<p class="h2">' . __('Keine Erfahrungsberichte vorhanden', 'affiliatetheme') . ' </p>'; } else { echo '<p class="h2">' . __('Kommentare', 'affiliatetheme') . '</p>'; }

comments_template();
?>