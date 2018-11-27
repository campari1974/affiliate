<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url() ); ?>">
	<div class="input-group">
		<input type="text" class="form-control" name="s" id="name" placeholder="<?php _e('Suche nach' , 'affiliatetheme'); ?>" value="<?php echo get_search_query(); ?>">
		<span class="input-group-btn">
			<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
		</span>
	</div>
</form>