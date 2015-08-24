<?php
session_start();
/* 
 * Load classes;
 * Main configuration file
 */
require_once 'classes/administration.php';

$new = new Administration;

/*
 * Minimal bootstrap file; 
 * acts like a router; 
 * loads the requested file;
 */

$module = 'admin';

/* 
 * Load config.app;
 * Main configuration file
 */
require_once 'config/config.app.php';

/*
 * Load functions library
 */
require_once 'library/functions.php';

/* 
 * Load class.Menu.php;
 * Menu generator class
 */
require_once 'library/class.Menu.php';

/* 
 * Load config.colors;
 * Skins configuration file
 */
require_once 'config/config.colors.php';

/* 
 * Load config.menus;
 * Generate Sidebar Menu
 */
require_once 'config/config.menus.php';

/* 
 * Load config.scripts;
 * Dynamically load JavaScript files in the header and footer
 */
require_once 'config/config.scripts.php';

/*
 * Requested page;
 * Index by default
 */
if(!isset($_SESSION['user_id'])){
	$page = isset($_GET['page']) ? $_GET['page'] : 'login';
	// header('Location: ?page=logout'); //change yoursite.com to the name of you site!!
}else{
	if( $_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { //have we expired?
		    //redirect to logout.php
		    header('Location: ?page=logout'); //change yoursite.com to the name of you site!!
		} else{ //if we haven't expired:
		    $_SESSION['last_activity'] = time(); //this was the moment of last activity.
		}
		$level = 1;
		$symbol_menu['role_id'] = 1;
		$symbol_menu['page_name'] = $_GET['page'];
		$menu_array = $new->get_role_allocated_to_pages($symbol_menu);
		$page_value = $_GET['page'];
		if($level == 1){
			$page = isset($_GET['page']) ? $_GET['page'] : 'index';	
		}else{
			if(is_array($menu_array)){
				if($menu_array['status'] == 0){
					if($page_value == "index"){
						$page = isset($_GET['page']) ? $_GET['page'] : 'error_page';	
					}else{
						?>
						<script language="JavaScript" type="text/javascript">
							setTimeout("location.href = '?page=error_page'",2000); // milliseconds, so 10 seconds = 10000ms
						</script>
						<?php
					}

				}else{
					$page = isset($_GET['page']) ? $_GET['page'] : 'index';	
				}
			}else{
				?>
				<script language="JavaScript" type="text/javascript">
					setTimeout("location.href = '?page=error_page'",2000); // milliseconds, so 10 seconds = 10000ms
				</script>
				<?php
			}
		}
		
}

$section = isset($_GET['section']) ? $_GET['section'] : 'index';

/* Load header */
require_once 'header.php';

/*
 * Load page;
 */
require_once 'pages/' . $page . '.php';

/* Load footer */
require_once 'footer.php';