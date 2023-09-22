
<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.updatenotification
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Uncomment the following line to enable debug mode (update notification email sent every single time)
// define('PLG_SYSTEM_UPDATENOTIFICATION_DEBUG', 1);

/**
 * Joomla! Update Notification plugin
 *
 * Sends out an email to all Super Users or a predefined email address when a new Joomla! version is available.
 *
 * This plugin is a direct adaptation of the corresponding plugin in Akeeba Ltd's Admin Tools. The author has
 * consented to relicensing their plugin's code under GPLv2 or later (the original version was licensed under
 * GPLv3 or later) to allow its inclusion in the Joomla! CMS.
 *
 * @since  3.5
 */
class PlgSystemjcnotification extends JPlugin
{


	public function __construct(& $subject, $config)
	{
			parent::__construct($subject, $config);
			$user = JFactory::getUser();
			$logo = $this->params->get('jcnotifylogo');
			$showlogo = $this->params->get('showlogo');
			$jcnotifymaseges = explode('/',$this->params->get('jcnotifymasege'));
			$showlatestart = $this->params->get('showlatestart');
			$text = $this->params->get('topmassage');
			
			if(strpos($text, "{username}") !== false) {
      		$text = str_replace("{username}",$user->name,$text);
    		}
			
			$config = JFactory::getConfig();
			$sitename = $config->get( 'sitename' );
			
			
			$info = getdate();
			$hour = $info['hours'];
			if ($hour >= 06 && $hour < 11) {
			$daytime =  "صبح بخیر";
			} elseif ($hour >= 11 && $hour < 14) {
			$daytime =  "ظهر بخیر";
			} elseif ($hour >= 14 && $hour < 19) {
			$daytime =  "عصر بخیر";
			} elseif (($hour >= 19 && $hour < 24 ) || ($hour >=24  && $hour < 6 )) {
			$daytime =  "شب بخیر";
			}
			
			
			
			
			
			$session = JFactory::getSession();
			$notify =  $session->get( 'jcnotify');
			$tpath = JUri::root() ;
			if($showlogo == 1){
			$jclogo = $tpath.'/'.$logo ;
			}
			
			
			if ((!$user->guest) and ($notify !='isdone')){
			
			$session = JFactory::getSession();
			$session->set( 'jcnotify', 'isdone' );
			$icon = "'"."<img src='".$jclogo. "' alt='".$sitename."' width='150' height='150' />"."'";
			
			foreach($jcnotifymaseges as $jcnotifymasege){
			
			$body .=  $jcnotifymasege .'\n' ;
			
			}
			
			
			echo '
			<script>
			if (!("Notification" in window)) {
			alert("This browser does not support desktop notification");
			}
			else if (Notification.permission === "granted") {
			var options = {
			icon: ' ."'$jclogo'" . ',
			body: "'. $body .'",
			
			dir : "rtl"
			};
			var notification = new Notification("'.$text.'",options);
			}
			else if (Notification.permission !== '."'denied'".') {
			Notification.requestPermission(function (permission) {
			if (!('."'permission'".' in Notification)) {
			Notification.permission = permission;
			}
			
			if (permission === "granted") {
			var options = {
			icon: ' ."'$jclogo'" . ',
			body: "'. $body .'",
			
			dir : "rtl"
			};
			
			var notification = new Notification("'.$text.'",options);
			}
			});
			}
			
			</script> ';
			}
      }
 }

