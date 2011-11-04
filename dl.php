<?php
/*
 *	mnlfolio v1.5.3
 *	by Morgan Cugerone - http://ipositives.net
 *	Last Modification: 20111105
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

if ($_REQUEST ['p'])
	$photo = $_REQUEST ['p'];

$filename = basename($photo);   
ini_set('session.cache_limiter', '');
header('Expires: Thu, 14 Apr 1981 03:28:00 GMT');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header("Content-Type: application/octet-stream");
header("Content-Disposition: disposition-type=attachment; filename=\"$filename\"");

readfile($photo);

?>