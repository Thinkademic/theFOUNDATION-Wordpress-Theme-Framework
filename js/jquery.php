<?php 
header('Content-type: text/javascript');   
header("Cache-Control: must-revalidate"); 
$offset = 72000 ; 
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT"; 
header($ExpStr);

if( get_query_var('dynamic') == 'themeoptions' ) {
	do_action('fdt_print_dynamic_themeoptions_js');
} else {
	do_action('fdt_print_dyanmic_galleries_js');
}

?>