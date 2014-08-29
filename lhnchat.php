<?php
/*
Plugin Name: LiveHelpNow Help Desk -- Chat Button
Plugin URI: http://livehelpnow.net
Description: LiveHelpNow Help Desk -- Chat Button
Version: 2.1.1
Author: LiveHelpNow
Author URI: http://livehelpnow.net
*/

// check version. only 2.8 WP support class multi widget system
global $wp_version;
if((float)$wp_version >= 2.8){

class LHNChat extends WP_Widget {
	
	/**
	 * constructor
	 */	 
	public function __construct() {
		parent::__construct(
			'lhn_chat', 
			'LiveHelpNow Help Desk -- Chat Button', 
			array(
				'description' => 'Widget to add Live Help Now\'s Chat Button to your Wordpress website.'
			)
		);	
	}
	
	/**
	 * display widget
	 */	 
	public function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		$account = empty($instance['account']) ? '' : $instance['account'];
		$account = str_replace("lhn", "", strtolower($account));
		$button = empty($instance['button']) ? '35' : $instance['button'];
		$invite = empty($instance['invite']) ? '0' : $instance['invite'];
		$window = empty($instance['window']) ? '0' : $instance['window'];
		$invitation = empty($instance['invitation']) ? '0' : $instance['invitation'];
		$department = empty($instance['department']) ? '0' : $instance['department'];
		$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		if(function_exists(get_current_user_id)){
			$userID = get_current_user_id();
			$user_data = get_userdata($userID);
			$lhnUserEmail = $user_data->user_email;
			$lhnUserName = mysql_real_escape_string($user_data->user_firstname." ".$user_data->user_lastname);
		}else{
			global $current_user;
			get_currentuserinfo();
			$lhnUserEmail = $current_user->user_email;
			$lhnUserName = mysql_real_escape_string($current_user->user_firstname." ".$current_user->user_lastname);
		}
	    
	    if($account && $button && is_numeric($button)){
		echo $before_widget;
		
		if($title && $title != ""){
			echo $before_title;	
			echo $title;
			echo $after_title;
		}
		?>
		<script type="text/javascript" data-cfasync="false">
				var lhnAccountN = "<?php echo $account; ?>";
				var lhnButtonN = <?php echo $button; ?>;
				var lhnInviteEnabled = <?php echo ($invite == "on") ? "1" : "0"; ?>; 
				var lhnWindowN = <?php echo $window; ?>;
				var lhnInviteN = <?php echo $invitation; ?>;
				var lhnDepartmentN = <?php echo $department; ?>; 
				var lhnCustom1 = '<?php echo $lhnUserEmail; ?>'; 
				var lhnCustom2 = '<?php echo $lhnUserName; ?>'; 
				var lhnCustom3 = '';
				var lhnPlugin = 'WP-<?php echo get_bloginfo('version'); ?>-Chat';
		</script>
		<a href="http://www.LiveHelpNow.net/" target="_blank" style="font-size:10px;" id="lhnHelp">Help Desk Software</a>
		<script src="//www.livehelpnow.net/lhn/widgets/chatbutton/lhnchatbutton-current.min.js" type="text/javascript" data-cfasync="false" id="lhnscript"></script>
		<?php
		echo $after_widget;
		}
		
	}
	
	/**
	 *	update/save function
	 */	 	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['account'] = strip_tags($new_instance['account']);
		$instance['button'] = strip_tags($new_instance['button']);
		$instance['invite'] = strip_tags($new_instance['invite']);
		$instance['invitation'] = strip_tags($new_instance['invitation']);
		$instance['window'] = strip_tags($new_instance['window']);
		$instance['department'] = strip_tags($new_instance['department']);
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	/**
	 *	admin control form
	 */	 	
	public function form($instance) {
		$account = isset($instance['account']) ? $instance['account'] : __('');
		$account = str_replace("lhn", "", strtolower($account));
		$button = isset($instance['button']) ? $instance['button'] : __('35');
		$invite = isset($instance['invite']) ? $instance['invite'] : __('off');
		$window = isset($instance['window']) ? $instance['window'] : __('0');
		$invitation = isset($instance['invitation']) ? $instance['invitation'] : __('0');
		$department = isset($instance['department']) ? $instance['department'] : __('0');
		$title = isset($instance['title']) ? $instance['title'] : __('Chat With Us');
		?>
		<p>
			<label for="<?php $this->get_field_id('account'); ?>">
				<?php _e( 'LiveHelpNow Account#:' ); ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('account'); ?>" name="<?php echo $this->get_field_name('account'); ?>" type="text" value="<?php echo esc_attr($account); ?>" />
			<?php
				echo is_numeric(str_replace("-1", "", esc_attr($account))) ? '<span style="color:green;">Accepted</span>' : '<span style="color:red;">Invalid</span>';
			?>
			<br />
			* Must be numeric
		</p>
		<p>
			<label for="<?php $this->get_field_id('button'); ?>">
				<?php echo 'LiveHelpNow Button#: &nbsp;&nbsp;<a href="http://www.livehelpnow.net/products/live_chat_system/buttons/" target="_blank" style="font-size:11px;text-decoration:none;">View Buttons</a>'; ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>" type="text" value="<?php echo esc_attr($button); ?>" />
			<?php
				echo is_numeric(esc_attr($button)) ? '<span style="color:green;">Accepted</span>' : '<span style="color:red;">Invalid</span>';
			?>
			<br />
			* Must be numeric
		</p>
		<p>
			<label for="<?php $this->get_field_id('invite'); ?>">
				<?php echo 'LiveHelpNow Automatic Chat Invitation:'; ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('invite'); ?>" name="<?php echo $this->get_field_name('invite'); ?>" type="checkbox" value="on" <?php if($invite == "on"){echo "checked";} ?> />
		</p>
		<p>
			<label for="<?php $this->get_field_id('window'); ?>">
				<?php echo 'LiveHelpNow Window ID:'; ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('window'); ?>" name="<?php echo $this->get_field_name('window'); ?>" type="text" value="<?php echo esc_attr($window); ?>" />
			<?php
				echo is_numeric(esc_attr($window)) ? '<span style="color:green;">Accepted</span>' : '<span style="color:red;">Invalid</span>';
			?>
			<br />
			* Must be numeric
		</p>
		<p>
			<label for="<?php $this->get_field_id('invitation'); ?>">
				<?php echo 'LiveHelpNow Invitation ID:'; ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('invitation'); ?>" name="<?php echo $this->get_field_name('invitation'); ?>" type="text" value="<?php echo esc_attr($invitation); ?>" />
			<?php
				echo is_numeric(esc_attr($invitation)) ? '<span style="color:green;">Accepted</span>' : '<span style="color:red;">Invalid</span>';
			?>
			<br />
			* Must be numeric
		</p>
		<p>
			<label for="<?php $this->get_field_id('department'); ?>">
				<?php echo 'LiveHelpNow Department ID:'; ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('department'); ?>" name="<?php echo $this->get_field_name('department'); ?>" type="text" value="<?php echo esc_attr($department); ?>" />
			<?php
				echo is_numeric(esc_attr($department)) ? '<span style="color:green;">Accepted</span>' : '<span style="color:red;">Invalid</span>';
			?>
			<br />
			* Must be numeric
		</p>
		<p>
			<label for="<?php $this->get_field_id('title'); ?>">
				<?php _e( 'LiveHelpNow Title:' ); ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<a href="http://www.livehelpnow.net/lnd/trial.aspx" target="_blank" style='font-size:14px; font-weight:bold;text-decoration:none;'>
				<strong>Get LiveHelpNow account</strong>
			</a><br />
			<a href="http://www.livehelpnow.net/alerter/" target="_blank" style='font-size:14px; font-weight:bold;text-decoration:none;'>
				<strong>Download Alterer Software</strong>
			</a><br />
			<a href="http://www.livehelpnow.net" target="_blank" style='font-size:14px; font-weight:bold;text-decoration:none;'>Get Help</a><br />
			<span style="font-size:9px;line-height:12px;">To remove the "Help Desk Software" link from your widget, add "-1" to the end of your Account #.</span>
		</p>
		<?php
	}
}

/* register widget when loading the WP core */
add_action('widgets_init', just_register_widgets);

function just_register_widgets(){
	// curl need to be installed
	register_widget('LHNChat');
}

}