<?php
/*
Plugin Name: LiveHelpNow Help Desk -- Chat Button
Plugin URI: http://livehelpnow.net
Description: LiveHelpNow Help Desk -- Chat Button
Version: 2.0
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
		echo $before_widget;
		$account = empty($instance['account']) ? '' : apply_filters('widget_title', $instance['account']);
		$button = empty($instance['button']) ? '' : apply_filters('widget_title', $instance['button']);
		$title = empty($instance['title']) ? '' : '<h3 style="margin-bottom:3px;">'.apply_filters('widget_title', $instance['title']).'</h3>';
		if(function_exists(get_current_user_id)){
			$userID = get_current_user_id();
			$user_data = get_userdata($userID);
			$lhnUserEmail = $user_data->user_email;
			$lhnUserName = $user_data->user_firstname." ".$user_data->user_lastname;
		}else{
			global $current_user;
			get_currentuserinfo();
			$lhnUserEmail = $current_user->user_email;
			$lhnUserName = $current_user->user_firstname." ".$current_user->user_lastname;
		}
		
		if(substr($account, -2)=="-1"){
		    $subText = "";
	    }else{
		    $subText = '<div style="font-size:10px;"><a title="Help desk software" href="http://www.LiveHelpNow.net/" style="font-size:10px;" target="_blank">Help desk software</a></div>';
	    }
	    $account = str_replace("-1","",$account);
					
		echo $title;
		?>
		<!--start http://www.livehelpnow.net  -->
		<!--This DIV object will show the live chat button, have it placed in the location on your website where you would like the live chat button to show-->
		<div id="lhnContainer">
			<div id="lhnChatButton"></div>
			<?php echo $subText; ?>
		</div>
		<!--You may install the following code in an external JavaScript file if you like-->
		<script type="text/javascript">
			if(lhnChatButton === undefined){
				var lhnChatButton = 1;
				var lhnAccountN = <?php echo $account ?>;
				var lhnButtonN = <?php echo $button ?>;
				var lhnVersion = 5.3; 
				var lhnJsHost = (("https:" == document.location.protocol) ? "https://" : "http://"); 
				var lhnInviteEnabled = 0; 
				var lhnInviteChime = 0; 
				var lhnWindowN = 0;
				var lhnDepartmentN = 0; 
				var lhnCustomInvitation = ''; 
				var lhnCustom1 = '<?php echo $lhnUserEmail; ?>'; 
				var lhnCustom2 = '<?php echo $lhnUserName; ?>'; 
				var lhnCustom3 = ''; 
				var lhnTrackingEnabled = 't';
				var lhnScriptSrc = lhnJsHost + 'www.livehelpnow.net/lhn/scripts/livehelpnow.aspx?lhnid=' + lhnAccountN + '&iv=' + lhnInviteEnabled + '&d=' + lhnDepartmentN + '&ver=' + lhnVersion + '&rnd=' + Math.random();
				lhnLoadEvent = addLHNButton(lhnScriptSrc,'append');
			}else{
				lhnLoadEvent = addLHNButton(<?php echo $button; ?>,'insert');
			}
			if (window.addEventListener) {
				window.addEventListener('load', function () {
					lhnLoadEvent;
				});
			}else{
				window.attachEvent('onload', function () {
					lhnLoadEvent;
				});
			}
			function addLHNButton(lhnbutton, lhntype){
		        element = document.getElementById('lhnContainer');
		        element.id = 'lhnContainerDone';
		        if(lhntype == 'insert'){
                	var lhnScript = document.createElement("a");lhnScript.href = "#";lhnScript.onclick = function(){OpenLHNChat();return false;};
					lhnScript.innerHTML = '<img id="lhnchatimg" alt="Live Help" border="0" nocache src="'+lhnJsHost+'www.livehelpnow.net/lhn/functions/imageserver.ashx?lhnid='+lhnAccountN+'&amp;navname=&amp;java=&amp;referrer=&amp;pagetitle=&amp;pageurl=&amp;t=f&amp;zimg='+lhnbutton+'&amp;d=0&amp;rndstr=999" />';
                	element.insertBefore(lhnScript,element.firstChild);
                }else{
	                var lhnScript = document.createElement("script");lhnScript.type = "text/javascript";lhnScript.src = lhnbutton;
	                element.appendChild(lhnScript);
                }
			}
		</script>
		<!--end http://www.livehelpnow.net  -->
		<?php
		echo $after_widget;
	}
	
	/**
	 *	update/save function
	 */	 	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['account'] = strip_tags($new_instance['account']);
		$instance['button'] = strip_tags($new_instance['button']);
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	
	/**
	 *	admin control form
	 */	 	
	public function form($instance) {
		$account = isset($instance['account']) ? $instance['account'] : __('');
		$button = isset($instance['button']) ? $instance['button'] : __('35');
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
		</p>
		<p>
			<label for="<?php $this->get_field_id('button'); ?>">
				<?php echo 'LiveHelpNow Button#: &nbsp;&nbsp;<a href="http://www.livehelpnow.net/products/live_chat_system/buttons/" target="_blank" style="font-size:11px;text-decoration:none;">View Buttons</a>'; ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>" type="text" value="<?php echo esc_attr($button); ?>" />
			<?php
				echo is_numeric(esc_attr($button)) ? '<span style="color:green;">Accepted</span>' : '<span style="color:red;">Invalid</span>';
			?>
		</p>
		<p>
			<label for="<?php $this->get_field_id('title'); ?>">
				<?php _e( 'LiveHelpNow Title:' ); ?>
			</label>
			<input class="" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<a href='http://www.livehelpnow.net/lnd/trial.aspx' target='_blank' style='font-size:14px; font-weight:bold;text-decoration:none;'>
				<strong>Get LiveHelpNow account</strong>
			</a><br />
			<a href="http://www.livehelpnow.net/alerter/" target="_blank" style='font-size:14px; font-weight:bold;text-decoration:none;'>
				<strong>Download Alterer Software</strong>
			</a><br />
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