<?php
/**
 * @package LiveHelpNow Help Desk
 * @author LiveHelpNow
 * @version 1.1.1
 */
/*
Plugin Name: LiveHelpNow Help Desk
Plugin URI: http://www.livehelpnow.net
Description: live chat button plugin by LiveHelpNow
Author: LiveHelpNow
Version: 1.1.1
Author URI: http://www.livehelpnow.net
*/

 function livehelpnow_widget()
{
    $options=get_option('livehelpnow');
    $clientid=$options['livehelpnow_clientid'];
    $clientid = str_replace("lhn","",strtolower($clientid));
    $buttonid=$options['livehelpnow_buttonid'];    
    $title=$options['livehelpnow_title'];
    if($title)
    {
     echo "<h3>$title</h3>";
    }
$button=<<<EOT
<!--start http://www.livehelpnow.net  --> <div style="text-align: center;white-space: nowrap;">
<div>   
<script type="text/javascript">
var lhnJsHost = (("https:" == document.location.protocol) ? "https://" : "http://"); document.write(unescape("%3Cscript src='" + lhnJsHost + "www.livehelpnow.net/lhn/scripts/lhnvisitor.aspx?div=&amp;zimg=$buttonid&amp;lhnid=$clientid&amp;iv=1&amp;zzwindow=0&amp;d=0&amp;custom1=&amp;custom2=&amp;custom3=' type='text/javascript'%3E%3C/script%3E"));</script>
</div>
<div><a title="Help Desk Software" href="http://www.LiveHelpNow.net/" style="font-size:10px;" target="_blank">Help Desk Software</a></div> </div> <!--end http://www.livehelpnow.net  -->
EOT;
    echo $button;
}
function livehelpnow_init()
{
register_sidebar_widget('LiveHelpNow Help Desk', 'livehelpnow_widget');
register_widget_control('LiveHelpNow Help Desk', 'livehelpnow_widgetcontrol');
}
add_action('init', 'livehelpnow_init');

function livehelpnow_widgetcontrol()
{
$options = get_option('livehelpnow');
if ( $_POST["livehelpnow_submit"] )
{
$options['livehelpnow_clientid'] = strip_tags( stripslashes($_POST["livehelpnow_clientid"] ) );
$options['livehelpnow_buttonid'] = strip_tags( stripslashes($_POST["livehelpnow_buttonid"] ) );
$options['livehelpnow_title'] = strip_tags( stripslashes($_POST["livehelpnow_title"] ) );
update_option('livehelpnow', $options);
}
$livehelpnow_clientid = $options['livehelpnow_clientid'];
$livehelpnow_buttonid = $options['livehelpnow_buttonid'];
$livehelpnow_title=$options['livehelpnow_title'];
include('livehelpnow-widget-control.php');
}
?>