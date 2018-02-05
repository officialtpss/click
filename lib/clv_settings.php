<?php
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
?> 
<div id="clv_form" class="wrap">
	<h1><?php _e('Clickavist API Settings', 'clickavist'); ?></h1>
	<form action="options.php" method="post">
		<?php wp_nonce_field('update-options'); ?>
		<div class="form-fields"> 
			<div class="row">
				<div class="left-col"><label id="clv_auth_key_lebel"><?php _e('Authorization Key:', 'clickavist'); ?></label></div>
				<div class="right-col">
					<input name="clv_auth_key" required type="text" class="regular-text all-options" id="clv_auth_key" value="<?php echo get_option('clv_auth_key'); ?>" placeholder="Enter authorization key .."/>
				</div>
			</div>

			<div class="row">
				<div class="left-col">
					<span class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'clickavist'); ?>" />
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="page_options" value="clv_auth_key,clv_con_list" />
					</span>
				</div>
				<div class="right-col"></div>
			</div>
		
		</div>
	</form>
</div>

