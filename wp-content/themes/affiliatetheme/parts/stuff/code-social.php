<?php
/*
 * VARS
 */
$networks = (is_array(get_field('socialbuttons_networks', 'option')) ? get_field('socialbuttons_networks', 'option') : array());
$show_signals = get_field('socialbuttons_signals', 'option');
$hide_summary = get_field('socialbuttons_signals_hide_summary', 'option');
$show_text = get_field('socialbuttons_text', 'option');
$btn_size = get_field('socialbuttons_size', 'option');
$url = at_get_current_url();

if('1' == $show_signals) {
	$socialSignals = new SocialSignals;
	$signals = $socialSignals->getSignals($networks);
}
?>
<div class="post-social">
	<div class="btn-group btn-group-social btn-group-justified <?php echo ((!$btn_size) ? 'btn-group-md' : 'btn-group-' . $btn_size); ?>">
		<?php if('1' == $show_signals && '1' != $hide_summary) { ?>
			<a class="btn btn-social btn-summary disabled hidden-xs" href="#" target="_blank" rel="nofollow">
				<i class="fa fa-heart"></i>
				<span class="count"><?php echo array_sum($signals); ?></span> 
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php echo _n( 'Signal', 'Signale', array_sum($signals), 'affiliatetheme' ) ?></span><?php } ?>
			</a>
		<?php } ?>
		
		<?php if(in_array('wa', $networks)) { ?>
		<a class="btn btn-social btn-whatsapp visible-xs" href="whatsapp://send?text=<?php echo urlencode(get_the_title()) . ' - ' . urlencode($url); ?>" target="_blank" rel="nofollow">
			<i class="fa fa-whatsapp"></i>
			<span class="count">1337</span>
			<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('WhatsApp', 'affiliatetheme'); ?></span><?php } ?>
		</a>
		<?php } ?>
		
		<?php if(in_array('fb_like', $networks)) { ?>
		<a class="btn btn-social btn-facebook-like" href="https://www.facebook.com/plugins/like.php?href=<?php echo urlencode($url); ?>" onclick="socialp(this, 'fb');return false;" target="_blank" rel="nofollow">
			<i class="fa fa-thumbs-up"></i>
			<?php if('1' == $show_signals) { ?>
				<span class="count"><?php echo $signals['fb_like']; ?></span>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php echo _n( 'Like', 'Likes', $signals['fb_like'], 'affiliatetheme' ) ?></span><?php } ?>
			<?php } else { ?>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('liken', 'affiliatetheme'); ?></span><?php } ?>
			<?php } ?>
		</a>
		<?php } ?>
		
		<?php if(in_array('fb_share', $networks)) { ?>
		<a class="btn btn-social btn-facebook-share" href="https://www.facebook.com/sharer.php?u=<?php echo $url; ?>" onclick="socialp(this, 'fb');return false;" target="_blank" rel="nofollow">
			<i class="fa fa-facebook"></i>
			<?php if('1' == $show_signals) { ?>
				<span class="count"><?php echo $signals['fb_share']; ?></span>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php echo _n( 'Share', 'Shares', $signals['fb_share'], 'affiliatetheme' ) ?></span><?php } ?>
			<?php } else { ?>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('teilen', 'affiliatetheme'); ?></span><?php } ?>
			<?php } ?>
		</a>
		<?php } ?>
		
		<?php if(in_array('twitter', $networks)) { ?>
		<a class="btn btn-social btn-twitter" href="https://twitter.com/share?url=<?php echo $url; ?>" onclick="socialp(this, 'twitter');return false;" target="_blank" rel="nofollow">
			<i class="fa fa-twitter"></i>
			<?php if('1' == $show_signals) { ?>
				<span class="count"><?php echo $signals['twitter']; ?></span>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php echo _n( 'Tweet', 'Tweets', $signals['twitter'], 'affiliatetheme' ) ?></span><?php } ?>
			<?php } else { ?>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('tweeten', 'affiliatetheme'); ?></span><?php } ?>
			<?php } ?>
		</a>
		<?php } ?>
		
		<?php if(in_array('gplus', $networks)) { ?>
		<a class="btn btn-social btn-google-plus" href="https://plus.google.com/share?url=<?php echo $url; ?>" onclick="socialp(this, 'gplus');return false;" target="_blank" rel="nofollow">
			<i class="fa fa-google-plus"></i>
			<?php if('1' == $show_signals) { ?>
				<span class="count"><?php echo $signals['gplus']; ?></span>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php echo _n( 'Share', 'Shares', $signals['gplus'], 'affiliatetheme' ) ?></span><?php } ?>
			<?php } else { ?>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('plussen', 'affiliatetheme'); ?></span><?php } ?>
			<?php } ?>
		</a>
		<?php } ?>
		
		<?php if(in_array('linkedin', $networks)) { ?>
		<a class="btn btn-social btn-linkedin" href="https://www.linkedin.com/cws/share?url=<?php echo $url; ?>" onclick="socialp(this, 'linkedin');return false;" target="_blank" rel="nofollow">
			<i class="fa fa-linkedin"></i>
			<?php if('1' == $show_signals) { ?>
				<span class="count"><?php echo $signals['linkedin']; ?></span>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php echo _n( 'Share', 'Shares', $signals['linkedin'], 'affiliatetheme' ) ?></span><?php } ?>
			<?php } else { ?>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('sharen', 'affiliatetheme'); ?></span><?php } ?>
			<?php } ?>
		</a>
		<?php } ?>
		
		<?php if(in_array('pinterest', $networks)) { ?>
		<a class="btn btn-social btn-pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&description=<?php the_title(); ?>" onclick="socialp(this, 'pinterest');return false;" target="_blank" rel="nofollow">
			<i class="fa fa-pinterest"></i>
			<?php if('1' == $show_signals) { ?>
				<span class="count"><?php echo $signals['pinterest']; ?></span>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php echo _n( 'Share', 'Shares', $signals['pinterest'], 'affiliatetheme' ) ?></span><?php } ?>
			<?php } else { ?>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('sharen', 'affiliatetheme'); ?></span><?php } ?>
			<?php } ?>
		</a>
		<?php } ?>
		
		<?php if(in_array('xing', $networks)) { ?>
		<a class="btn btn-social btn-xing" href="https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url=<?php echo $url; ?>" onclick="socialp(this, 'xing');return false;" target="_blank" rel="nofollow">
			<i class="fa fa-xing"></i>
			<?php if('1' == $show_signals) { ?>
				<span class="count"><?php echo $signals['xing']; ?></span>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php echo _n( 'Share', 'Shares', $signals['xing'], 'affiliatetheme' ) ?></span><?php } ?>
			<?php } else { ?>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('sharen', 'affiliatetheme'); ?></span><?php } ?>
			<?php } ?>
		</a>
		<?php } ?>

		<?php if(in_array('mail', $networks)) { ?>
			<a class="btn btn-social btn-mail" href="mailto:?body=<?php echo $url; ?>&subject=<?php _e('Meine Empfehlung f&uuml;r dich', 'affiliatetheme'); ?>" target="_blank" rel="nofollow">
				<i class="fa fa-envelope"></i>
				<?php if('1' != $show_text) { ?><span class="hidden-xs hidden-sm"><?php _e('mailen', 'affiliatetheme'); ?></span><?php } ?>
			</a>
		<?php } ?>
	</div>
</div>