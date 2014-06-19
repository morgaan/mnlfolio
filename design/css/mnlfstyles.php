<?php
/*
 *	mnlfolio
 *	by Morgan Cugerone - http://ipositives.net
 *
 *	For more information, visit:
 *	http://morgan.cugerone.com/mnlfolio
 *
 *	Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
 *  	- Free for use in both personal and commercial projects
 *		- Attribution requires leaving author name, author link, and the license info intact.
 *	
 *  Thanks: Jan Odvarko (http://odvarko.cz) for developing this wonderful piece of jscolor code
 *  		Dan Coulter (dan@dancoulter.com / http://phpflickr.com) for bringing this great phpflickr interface
 *			To every friends and relatives who supported and helped me in the achievement of this project.
 */

include("config/css.php");
?>

body {
	background-color: #<? echo $mnlfbodybgcolor; ?>;
}

.body {

	color: #<? echo $mnlfbodyfontcolor; ?>;
	font-size: <? echo $mnlfbodyfontsize; ?>;
	font-family: <? echo $mnlfbodyfontfamily; ?>;
	font-weight: <? echo $mnlfbodyfontweight; ?>;
	font-style: <? echo $mnlfbodyfontstyle; ?>;
	text-decoration : <? echo $mnlfbodytextdecoration; ?>;	
	text-align : <? echo $mnlfbodytextalign; ?>;
	
}

a {
	color: #<? echo $mnlflinksfontcolor; ?>;
	font-size: <? echo $mnlflinksfontsize; ?>;
	font-family: <? echo $mnlflinksfontfamily; ?>;
	font-weight: <? echo $mnlflinksfontweight; ?>;
	font-style: <? echo $mnlflinksfontstyle; ?>;
	text-decoration : <? echo $mnlflinkstextdecoration; ?>;	
	text-align : <? echo $mnlflinkstextalign; ?>;
}

.title {
	color: #<? echo $mnlftitlefontcolor; ?>;
	font-size: <? echo $mnlftitlefontsize; ?>;
	font-family: <? echo $mnlftitlefontfamily; ?>;
	font-weight: <? echo $mnlftitlefontweight; ?>;
	font-style: <? echo $mnlftitlefontstyle; ?>;
	text-decoration : <? echo $mnlftitletextdecoration; ?>;	
	text-align : <? echo $mnlftitletextalign; ?>;
}

a.contact {
	color: #<? echo $mnlfcontactfontcolor; ?>;
	font-size: <? echo $mnlfcontactfontsize; ?>;
	font-family: <? echo $mnlfcontactfontfamily; ?>;
	font-weight: <? echo $mnlfcontactfontweight; ?>;
	font-style: <? echo $mnlfcontactfontstyle; ?>;
	text-decoration : <? echo $mnlfcontacttextdecoration; ?>;	
	text-align : <? echo $mnlfcontacttextalign; ?>;
}

.copyright {
	color: #<? echo $mnlfcopyrightfontcolor; ?>;
	font-size: <? echo $mnlfcopyrightfontsize; ?>;
	font-family: <? echo $mnlfcopyrightfontfamily; ?>;
	font-weight: <? echo $mnlfcopyrightfontweight; ?>;
	font-style: <? echo $mnlfcopyrightfontstyle; ?>;
	text-decoration : <? echo $mnlfcopyrighttextdecoration; ?>;	
	text-align : <? echo $mnlfcopyrighttextalign; ?>;
}

td.thumb {   
	border-width: <? echo $mnlftdthumbborderwidth; ?>;
	border-style: <? echo $mnlftdthumbborderstyle; ?>;
	border-color:  #<? echo $mnlftdthumbbordercolor; ?>;
}

td.thumbselected {
	border-width: <? echo $mnlftdthumbselectedborderwidth; ?>;
	border-style: <? echo $mnlftdthumbselectedborderstyle; ?>;
	border-color:  #<? echo $mnlftdthumbselectedbordercolor; ?>;
}

img.thumb {
	border-width: <? echo $mnlfimgthumbborderwidth; ?>;
	border-style: <? echo $mnlfimgthumbborderstyle; ?>;
	border-color:  #<? echo $mnlfimgthumbbordercolor; ?>;
	opacity:<? echo $mnlfimgthumbopacity; ?>;
	display:block;
}

img.thumbselected {
	border-width: <? echo $mnlfimgthumbselectedborderwidth; ?>;
	border-style: <? echo $mnlfimgthumbselectedborderstyle; ?>;
	border-color:  #<? echo $mnlfimgthumbselectedbordercolor; ?>;
	opacity:<? echo $mnlfimgthumbselectedopacity; ?>;
	display:block;
}

img.thumb:hover {
	border-width: <? echo $mnlfimgthumbhoverborderwidth; ?>;
	border-style: <? echo $mnlfimgthumbhoverborderstyle; ?>;
	border-color:  #<? echo $mnlfimgthumbhoverbordercolor; ?>;
	opacity:<? echo $mnlfimgthumbhoveropacity; ?>;
}

img.photo {
	position: relative;
	border-width: <? echo $mnlfimgphotoborderwidth; ?>;
	border-style: <? echo $mnlfimgphotoborderstyle; ?>;
	border-color:  #<? echo $mnlfimgphotobordercolor; ?>;
	display:block;
	z-index: 2;
}

div.photo:before {
	content: '<? echo $strImageLoadingMessage; ?>';
	display: block;
	position: absolute;
	width: inherit;
	top: 48%;
	z-index: 1;
}

div.photo {
	position: relative;
	margin: 0 auto;
	border-width: <? echo $mnlfdivphotoborderwidth; ?>;
	border-style: <? echo $mnlfdivphotoborderstyle; ?>;
	border-color:  #<? echo $mnlfdivphotobordercolor; ?>;
}

td.photoTitle {
	color: #<? echo $mnlfphototitlefontcolor; ?>;
	font-size: <? echo $mnlfphototitlefontsize; ?>;
	font-family: <? echo $mnlfphototitlefontfamily; ?>;
	font-weight: <? echo $mnlfphototitlefontweight; ?>;
	font-style: <? echo $mnlfphototitlefontstyle; ?>;
	text-decoration : <? echo $mnlfphototitletextdecoration; ?>;
	text-align : <? echo $mnlfphototitletextalign; ?>;	
}

td.photoDescription {
	color: #<? echo $mnlfphotodescriptionfontcolor; ?>;
	font-size: <? echo $mnlfphotodescriptionfontsize; ?>;
	font-family: <? echo $mnlfphotodescriptionfontfamily; ?>;
	font-weight: <? echo $mnlfphotodescriptionfontweight; ?>;
	font-style: <? echo $mnlfphotodescriptionfontstyle; ?>;
	text-decoration : <? echo $mnlfphotodescriptiontextdecoration; ?>;	
	text-align : <? echo $mnlfphotodescriptiontextalign; ?>;	
}

.photoNavigationControls a {
	color: #<? echo $mnlfphotonavigationcontrolsfontcolor; ?>;
	font-size: <? echo $mnlfphotonavigationcontrolsfontsize; ?>;
	font-family: <? echo $mnlfphotonavigationcontrolsfontfamily; ?>;
	font-weight: <? echo $mnlfphotonavigationcontrolsfontweight; ?>;
	font-style: <? echo $mnlfphotonavigationcontrolsfontstyle; ?>;
	text-decoration : <? echo $mnlfphotonavigationcontrolstextdecoration; ?>;
}

.thumbnailsNavigationControls a {
	color: #<? echo $mnlfthumbnailsnavigationcontrolsfontcolor; ?>;
	font-size: <? echo $mnlfthumbnailsnavigationcontrolsfontsize; ?>;
	font-family: <? echo $mnlfthumbnailsnavigationcontrolsfontfamily; ?>;
	font-weight: <? echo $mnlfthumbnailsnavigationcontrolsfontweight; ?>;
	font-style: <? echo $mnlfthumbnailsnavigationcontrolsfontstyle; ?>;
	text-decoration : <? echo $mnlfthumbnailsnavigationcontrolstextdecoration; ?>;
}

div.logo {
	color: #<? echo $mnlflogofontcolor; ?>;
	font-size: <? echo $mnlflogofontsize; ?>;
	font-family: <? echo $mnlflogofontfamily; ?>;
	font-weight: <? echo $mnlflogofontweight; ?>;
	font-style: <? echo $mnlflogofontstyle; ?>;
	text-decoration : <? echo $mnlflogotextdecoration; ?>;	
	text-align : <? echo $mnlflogotextalign; ?>;
}

a.logo {
	color: #<? echo $mnlflogofontcolor; ?>;
	font-size: <? echo $mnlflogofontsize; ?>;
	font-family: <? echo $mnlflogofontfamily; ?>;
	font-weight: <? echo $mnlflogofontweight; ?>;
	font-style: <? echo $mnlflogofontstyle; ?>;
	text-decoration : <? echo $mnlflogotextdecoration; ?>;	
	text-align : <? echo $mnlflogotextalign; ?>;
}

div.white {
	float: right;
	border: 1px solid #C1CCCc;
	margin-left:3px;
	width: 15px;
	height: 15px;
	background: #fff;
	cursor: pointer;
}

div.grey {
	float: right;
	border: 1px solid #C1CCCc;
	margin-left:3px;
	width: 15px;
	height: 15px;
	background: #666;
	cursor: pointer;
}

div.black {
	float: right;
	border: 1px solid #C1CCCc;
	margin-left:3px;
	width: 15px;
	height: 15px;
	background: #000;
	cursor: pointer;
}