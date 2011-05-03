<?php 
header('Content-type: text/css');
header("Cache-Control: must-revalidate"); 
$offset = 72000 ; 
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT"; 
header($ExpStr);



if( get_query_var('dynamic') == 'themeoptions' ) {
	do_action('fdt_print_dyanmic_css');
} else {
	do_action('fdt_print_dyanmic_galleries_css');
	css_nivoslider();
}


function css_nivoslider() {

	$csspostid = get_query_var('csspostid');
	$meta = get_post_meta($csspostid, THEMECUSTOMMETAKEY, true);	
	
	$STYLESHEETPATH = get_stylesheet_directory_uri();;
	
	if($meta["gallery_imagesize"] != "" ){	
		$width = get_option($meta["gallery_imagesize"].'_size_w');
		$height = get_option($meta["gallery_imagesize"].'_size_h');	
	} else {
		$width = 540;
		$height = 360;
	}	

	
if($meta["gallery_type"] == "nivoslider" ){	
print <<<END
	/* The Nivo Slider styles */
	.nivoslider {
		margin-top: 30px;
		margin-bottom: 30px;
		position:relative;
	}
	.nivoslider img {
		position:absolute;
		top:0px;
		left:0px;
	}
	/* If an image is wrapped in a link */
	.nivoslider a.nivo-imageLink {
		position:absolute;
		top:0px;
		left:0px;
		width:100%;
		height:100%;
		border:0;
		padding:0;
		margin:0;
		z-index:6;
		display:none;
	}
	/* The slices and boxes in the Slider */
	.nivo-slice {
		display:block;
		position:absolute;
		z-index:5;
		height:100%;
	}
	.nivo-box {
		display:block;
		position:absolute;
		z-index:5;
	}
	
	
	/* Caption styles */
	.nivo-caption {
		position:absolute;
		left:0px;
		bottom:0px;
		background:#000;
		color:#fff;
		width:100%;
		z-index:8;
	}
	.nivo-caption p {
		padding:10px;
		margin:0;
	}
	.nivo-caption a {
		display:inline !important;
	}
	.nivo-html-caption {
		display:none;
	}
	
	/* Direction nav styles (e.g. Next & Prev) */
	.nivo-directionNav a {
		position:absolute;
		top:45%;
		z-index:9;
		cursor:pointer;
		background: url({$STYLESHEETPATH}/images/gradient/black50.png) repeat top left;
		display: block;
		padding: 5px 10px;
		color: #fff;
	}

	.nivo-directionNav a:hover {
	    background: #000;
		color: #ccc;
	}	
	
	.nivo-prevNav {
		left:0px;
	}
	.nivo-nextNav {
		right:0px;
	}
	
	
	
	/* Control  BULLET NAV STYLES (e.g. 1,2,3...) */
	.nivoslider .nivo-controlNav
	{
		position: absolute;
		right: 0;
		bottom:  -22px;
		text-align: center !important;
		width: 100%;
	}
	.nivoslider .nivo-controlNav A
	{
		display: inline-block;
		width: 22px;
		height: 22px;
		background: url({$STYLESHEETPATH}/images/bullets/black.png) no-repeat center center;
		text-indent: -9999px;
		border: 0;
		margin-right: 3px;
		cursor: pointer;
		
	}
	.nivoslider .nivo-controlNav A.active, 	.nivoslider .nivo-controlNav A:hover
	{
		background: url({$STYLESHEETPATH}/images/bullets/green.png) no-repeat center center;
	}

	/* Control nav styles (e.g. 1,2,3...) */
	#slider3 .nivo-controlNav
	{
		position: absolute;
		left: 185px;
		bottom: -70px;
	}
	#slider3 .nivo-controlNav A
	{
		display: inline;
	}
	#slider3 .nivo-controlNav IMG
	{
		display: inline;
		position: relative;
		margin-right: 10px;
		-moz-box-shadow: 0px 0px 5px #333;
		-webkit-box-shadow: 0px 0px 5px #333;
		box-shadow: 0px 0px 5px #333;
	}
	#slider3 .nivo-controlNav A.active IMG
	{
		border: 1px solid #000;
	}
	

END;

}
}

?>