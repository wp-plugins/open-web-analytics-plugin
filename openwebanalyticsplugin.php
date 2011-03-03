<?php
/*
Plugin Name: Open Web Analytics Plugin
Plugin URI: http://www.chrische.de/open-web-analytics-wordpress-plugin
Description: This plugin makes it simple to add the Open Web Analytics tracking-code to your wordpress. <a href="options-general.php?page=openwebanalytics.php">Configuration Page</a>
Author: Christian Schmidt
Version: 1.3
Author URI: http://www.chrische.de/
License: GPL

Derived from Jules Stuifbergen's Piwik Analytics plugin (which is based on Joost de Valk's Google Analytics for Wordpress plugin)

*/

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

$siteid = "1";

/*
 * Admin User Interface
 */

if ( ! class_exists( 'OWA_Admin' ) ) {

	class OWA_Admin {
			
		function add_config_page() {
			global $wpdb;
			if ( function_exists('add_options_page') ) {
				add_options_page('Open Web Analytics Configuration', 'Open Web Analytics', 9, basename(__FILE__), array('OWA_Admin','config_page'));
			}
			
			
		} // end add_OWA_config_page()

		function config_page() {
			if ( $_GET['reset'] == "true") {
				$options['owa_host'] = '';
				$options['owa_baseurl'] = '/owa/';
				$options['admintracking'] = false;
				$options['dltracking'] = true;
				update_option('OpenWebAnalyticsPP',$options);
			}
				
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Open Web Analytics options.'));
				check_admin_referer('analyticspp-config');
				$options['siteid'] = $_POST['siteid'];

				if (isset($_POST['owa_baseurl']) && $_POST['owa_baseurl'] != "") 
					$options['owa_baseurl'] 	= strtolower($_POST['owa_baseurl']);

				if (isset($_POST['owa_host']) && $_POST['owa_host'] != "") 
					$options['owa_host'] 	= strtolower($_POST['owa_host']);

				if (isset($_POST['dltracking'])) {
					$options['dltracking'] = true;
				} else {
					$options['dltracking'] = false;
				}

				if (isset($_POST['admintracking'])) {
					$options['admintracking'] = true;
				} else {
					$options['admintracking'] = false;
				}

				update_option('OpenWebAnalyticsPP', $options);
			}

			$options  = get_option('OpenWebAnalyticsPP');
			?>
			<div class="wrap">
				<script type="text/javascript">
					function toggle_help(ele, ele2) {
						var expl = document.getElementById(ele2);
						if (expl.style.display == "block") {
							expl.style.display = "none";
							ele.innerHTML = "What's this?";
						} else {
							expl.style.display = "block";
							ele.innerHTML = "Hide explanation";
						}
					}
				</script>
				<h2>Open Web Analytics Configuration</h2>
				<form action="" method="post" id="analytics-conf">
					<table class="form-table" style="width:100%;">
					<?php
					if ( function_exists('wp_nonce_field') )
						wp_nonce_field('analyticspp-config');
					?>
					<tr>
						<th scope="row" style="width:400px;" valign="top">
							<label for="siteid">Open Web Analytics site id</label> <small><a href="#" onclick="javascript:toggle_help(this, 'expl');">What's this?</a></small>
						</th>
						<td>
							<input id="siteid" name="siteid" type="text" size="32" maxlength="32" value="<?php echo $options['siteid']; ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /><br/>
							<div id="expl" style="display:none;">
								<h3>Explanation</h3>
								<p>Open Web Analytics is a statistics service provided
									free of charge under the GPL v2 license.
									If you don't have a Open Web Analytics installed, you can get it at
									<a href="http://openwebanalytics.com/">openwebanalytics.com</a>.</p>

								<p>In the Open Web Analytics interface, when you "Add a Site" (Administration -> Tracked Sites -> Add a Site)
									you are shown a piece of JavaScript that
									you are told to insert into the page. Also, you see a "Site ID" for the website.
									This is aunique string that identifies the website you 
									just defined. (For Example Site ID "abcdefghijklmnopqrstuvwxyz12345678").
								<p>Once you have entered your site id in
								   the box above your pages will be trackable by
									Open Web Analytics.</p>
							</div>
						</td>
					</tr>							
					<tr>
						<th scope="row" valign="top">
							<label for="dltracking">Track downloads</label><br/>
							<small>(default is YES)</small>
						</th>
						<td>
							<input type="checkbox" id="dltracking" name="dltracking" <?php if ($options['dltracking']) echo ' checked="unchecked" '; ?>/> 
						</td>
					</tr>
					<tr>
						<th scope="row" style="width:400px;" valign="top">
							<label for="owa_host">Hostname of the OWA server (optional)</label> <small><a href="#" onclick="javascript:toggle_help(this, 'expl3');">What's this?</a></small>
						</th>
						<td>
							<input id="owa_host" name="owa_host" type="text" size="40" maxlength="99" value="<?php echo $options['owa_host']; ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /><br/>
							<div id="expl3" style="display:none;">
								<h3>Explanation</h3>
								<p>Example: www.yourdomain.com -- Leave blank (default) if this is the same as your blog. Do NOT include the http:// bit.</p>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" style="width:400px;" valign="top">
							<label for="owa_baseurl">Base URL path of OWA installation</label> <small><a href="#" onclick="javascript:toggle_help(this, 'expl2');">What's this?</a></small>
						</th>
						<td>
							<input id="owa_baseurl" name="owa_baseurl" type="text" size="40" maxlength="99" value="<?php echo $options['owa_baseurl']; ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /><br/>
							<div id="expl2" style="display:none;">
								<h3>Explanation</h3>
								<p>The URL path to the OWA installation. E.g. /owa/ or /stats/. Don't forget the trailing slash!</p>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="admintracking">Track the admin user too</label><br/>
							<small>(default is not to)</small>
						</th>
						<td>
							<input type="checkbox" id="admintracking" name="admintracking" <?php if ($options['admintracking']) echo ' checked="checked" '; ?>/> 
						</td>
					</tr>
					</table>
					<p style="border:0;" class="submit"><input type="submit" name="submit" value="Update Settings &raquo;" /></p>
				</form>
				<p>All options set? Then <a href="http://<?php if ($options['owa_host']) { echo $options['owa_host']; }else{ echo $_SERVER['HTTP_HOST'];}; echo $options['owa_baseurl']; ?>" title="OWA admin url" target="_blank">check out your stats!</a>
			</div>
			<?php
			if (isset($options['siteid'])) {
				if ($options['siteid'] == "") {
					add_action('admin_footer', array('OWA_Admin','warning'));
				} else {
					if (isset($_POST['submit'])) {
						if ($_POST['siteid'] != $options['siteid'] ) {
							add_action('admin_footer', array('OWA_Admin','success'));
						}
					}
				}
			} else {
				add_action('admin_footer', array('OWA_Admin','warning'));
			}

		} // end config_page()

		function restore_defaults() {
			$options['owa_host'] = '';
			$options['owa_baseurl'] = '/owa/';
			$options['admintracking'] = false;
			$options['dltracking'] = true;
			update_option('OpenWebAnalyticsPP',$options);
		}
		
		function success() {
			echo "
			<div id='analytics-warning' class='updated fade-ff0000'><p><strong>Congratulations! You have just activated Open Web Analytics.</p></div>
			<style type='text/css'>
			#adminmenu { margin-bottom: 7em; }
			#analytics-warning { position: absolute; top: 7em; }
			</style>";
		} // end success()

		function warning() {
			echo "
			<div id='analytics-warning' class='updated fade-ff0000'><p><strong>Open Web Analytics is not active.</strong> You must <a href='options-general.php?page=openwebanalytics.php'>enter your Site ID</a> for it to work.</p></div>";
		} // end warning()

	} // end class OWA_Admin

} //endif


/**
 * Code that actually inserts stuff into pages.
 */
if ( ! class_exists( 'OWA_Filter' ) ) {
	class OWA_Filter {

		/*
		 * Insert the tracking code into the page
		 */
		function spool_analytics() {
			?>
<!-- OWA plugin active -->
<?php
			
			$options  = get_option('OpenWebAnalyticsPP');
			
			if ($options["siteid"] != "" && (!current_user_can('edit_users') || $options["admintracking"]) && !is_preview() ) { 
?><!-- Open Web Analytics code inserted by Open Web Analytics Wordpress plugin by Christian Schmidt http://www.chrische.de/open-web-analytics-for-wordpress -->
				<!-- Start Open Web Analytics Tracker -->
				<script type="text/javascript">
				//<![CDATA[
				<?php if ( $options['owa_host'] ) { 
				?>var owa_baseUrl = document.location.protocol + "//" + "<?php echo $options['owa_host']; ?>" + "<?php echo $options['owa_baseurl']; ?>";
				<?php } else { 
				?>var owa_baseUrl = document.location.protocol + "//" + document.location.host + "<?php echo $options['owa_baseurl'] ?>";
				<?php }; 
				?>var owa_cmds = owa_cmds || [];
				owa_cmds.push(['setSiteId', '<?php echo $options['siteid']; ?>']);
				owa_cmds.push(['trackPageView']);
				owa_cmds.push(['trackClicks']);
				owa_cmds.push(['trackDomStream']);
				
				(function() {
					var _owa = document.createElement('script'); _owa.type = 'text/javascript'; _owa.async = true;
					owa_baseUrl = ('https:' == document.location.protocol ? window.owa_baseSecUrl || owa_baseUrl.replace(/http:/, 'https:') : owa_baseUrl );
					_owa.src = owa_baseUrl + 'modules/base/js/owa.tracker-combined-min.js';
					var _owa_s = document.getElementsByTagName('script')[0]; _owa_s.parentNode.insertBefore(_owa, _owa_s);
				}());
				//]]>
				</script>
				<!-- End Open Web Analytics Code -->
				
				
				
				<!-- /Open Web Analytics -->
	<?php
			}
		}


	} // class OWA_Filter
} // endif

if (function_exists("get_option")) {
	if ($wp_siteid_takes_precedence) {
		$options  = get_option('OpenWebAnalyticsPP');
		$siteid = $options['siteid'];
	}
} 

$gaf = new OWA_Filter();

$options  = get_option('OpenWebAnalyticsPP',"");

if ($options == "") {
	$options['owa_host'] = '';
	$options['owa_baseurl'] = '/owa/';
	$options['dltracking'] = true;
	update_option('OpenWebAnalyticsPP',$options);
}

// adds the menu item to the admin interface
add_action('admin_menu', array('OWA_Admin','add_config_page'));


// adds the footer so the javascript is loaded
add_action('wp_footer', array('OWA_Filter','spool_analytics'));	




?>
