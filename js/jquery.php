<?php 
header('Content-type: text/javascript');   
header("Cache-Control: must-revalidate"); 
$offset = 72000 ; 
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT"; 
header($ExpStr);
#require_once('../../../../wp-load.php');
#require_once('../../../../wp-includes/post.php');

if( get_query_var('jqids') != '' ) {
print <<<END
/* =========================
AUTO GENERATED JQUERY 
========================= */

END;

do_action('fdt_print_dynamic_js');
}

?>