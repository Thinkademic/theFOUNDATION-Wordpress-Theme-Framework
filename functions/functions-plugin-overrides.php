<?php
/**************************************************************
  QTRANSLATE V. 2.5.1.8
  ADD SUPPORT FOR TAXNOMY EDITING
**************************************************************/
function qtranslate_edit_taxonomies(){
	$args=array(
		'public' => true ,
		'_builtin' => false
	); 
	$output = 'object'; 	// or objects
	$operator = 'and'; 	// 'and' or 'or'

	$taxonomies = get_taxonomies($args,$output,$operator); 

	if  ($taxonomies) {
	  foreach ($taxonomies  as $taxonomy ) {
			add_action( $taxonomy->name.'_add_form', 'qtrans_modifyTermFormFor');
			add_action( $taxonomy->name.'_edit_form', 'qtrans_modifyTermFormFor');	  	
		
	  }
	}

}
add_action('admin_init', 'qtranslate_edit_taxonomies');

?>