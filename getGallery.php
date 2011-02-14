<?
/*
 *	mnlfolio v1.0.0
 *	by Morgan Cugerone - http://ipositives.net
 *	Last Modification: 20010117
 *
 *	For more information, visit:
 *	http://ipositives.net/mnlfolio
 *
 *	Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
 *  	- Free for use in both personal and commercial projects
 *		- Attribution requires leaving author name, author link, and the license info intact.
 *	
 *  Thanks: Jan Odvarko (http://odvarko.cz) for developing this wonderful piece of jscolor code
 *  		Dan Coulter (dan@dancoulter.com / http://phpflickr.com) for bringing this great phpflickr interface
 *			To every friends and relatives who supported and helped me in the achievement of this project.
 */

require_once("phpFlickr.php");

foreach($_REQUEST as $key => $value) $$key=$value;

require_once("mnlfLib.php");


if(isset($photoid)) {

?>

	<center><br /><br />
<? 		
		if(isset($setPage))
		 echo getViewerLayout($setId,$setPage,$photoid,$p,$n,$u,$d);
?>

	</center>


<?

}

?>