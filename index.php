<?php
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
 *  Thanks: Jan Odvarko (http://odvarko.cz) for developing this wonderful peace of jscolor code
 *  		Dan Coulter (dan@dancoulter.com / http://phpflickr.com) for bringing this great phpflickr interface
 *			To every friends and relatives who supported and helped me in the achievement of this project.
 */

// ====================================
// = Install config files if necesary =
// ====================================
$defaultConfigFile = "default/mnlfConfig.php";
$defaultCSSFile = "default/css.php";
$userConfigFile = "config/mnlfConfig.php";
$userCSSFile = "config/css.php";
$cacheDirectory = "cache";

if (!file_exists($cacheDirectory)) {	
	if (is_writable(".")) {
		mkdir($cacheDirectory, 0777);
	}
	else
		echo "Can't create cache directory";
}

if (!file_exists("config")) {	
	if (is_writable("."))
		mkdir("config", 0777);
	else
		echo "Can't create configuration directory";
}

if (!file_exists($userConfigFile)) {

	if (is_writable(".")) {
		$defaultConfigFileHandle = file ($defaultConfigFile);
		$userConfigFileHandle = fopen($userConfigFile, "w+");
		fwrite($userConfigFileHandle, file_get_contents($defaultConfigFile));
		fclose($userConfigFileHandle);
	} else
	   echo "Can't create configuration file";
}

if (!file_exists($userCSSFile)) {
	if (is_writable(".")) {
		$defaultCSSFileHandle = file ($defaultCSSFile);
		$userCSSFileHandle = fopen($userCSSFile, "w+");
		fwrite($userCSSFileHandle, file_get_contents($defaultCSSFile));
		fclose($userCSSFileHandle);
	} else
	   echo "Can't create CSS file";
}



foreach($_REQUEST as $key => $value) $$key=$value;
require_once("mnlfLib.php");

// =============================================================================
// = If none username has been define, stop here and go straight to Admin page =
// =============================================================================
if($strUsername == NULL) {
	
	?>
	
		<SCRIPT language="JavaScript">
		window.location="mnlfAdmin.php";
		</SCRIPT>
	
	<?
}

// =================================================
// = If a default set is set load the page with it =
// =================================================
if(!isset($setid) && isset($strDefaultSet) && $strDefaultSet != NULL) {
	?>
	
		<SCRIPT language="JavaScript">
		<? echo "window.location=\"index.php?setid=".$strDefaultSet."\""; ?>
		</SCRIPT>
	
	<?
}

$f = getFlickrObjectInstance(true);
//$f->setToken($strAuthToken);
//$f->enableCache("fs", $strCacheDir, $nCacheTimeToLive);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta name="description" <? echo "content=\"$strMetaDescription\""; ?> />
<meta name="keywords" <? echo "content=\"$strMetaKeywords\""; ?> />
<meta name="author" <? echo "content=\"$strMetaAuthor\""; ?> />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<title><? echo $strPageTitle; ?></title>
<style type="text/css">
<? include("design/css/mnlfstyles.php"); ?>
</style>

<?
//#######################//
// JAVASCRIPT FUNCTIONS
//#######################//
include("js.php");
?>

</head> 

<?
//#######################//
//		  BODY
//#######################//
?>

<body onkeydown="getArrows(event)" onload="init()" id="body" >


<?
//#######################//
//		ENTETE
//#######################//
?>

<table width="100%">
  <tr>
   <td align="left" valign="top">
<?
	if($boolShowLogo)
		echo "<a href=\"$strLogoLink\"><img src=\"design/images/logos/".getFileListSelectedValue("Logo")."\" border=\"0\" /></a>"; 
?>
   </td>
   <td align="right">
<?
	if($boolShowBackgroundColorPicker) {
?>
		 <div onclick="changeBackgroundColor('#000000')" class="black"></div>
		 <div onclick="changeBackgroundColor('#666666')" class="grey"></div>
		 <div onclick="changeBackgroundColor('#FFFFFF')" class="white"></div>
<?
}

	if($boolShowContact) {
?>
		 <a class="contact" <? echo "href=\"mailto:".$strContactEmail."\""; ?>><? echo $strContactLabel; ?></a>
<?
	}

	if($boolShowContact && $boolShowBackgroundColorPicker) {
?>

	&nbsp;&middot;&nbsp;

<?
	}
?>
   </td>
  </tr>
</table>
   <br/>
   <center>
<?
		if(!isset($setid))
			getNavigationMenu();
		else
			getNavigationMenu($setid);
		
?>
   </center>

<?
//#######################//
//		GALLERY
//#######################//
?>


<div id="gallery"></div>

<table class="body" border="0" align="center">
	<tr>
		<td align="center">
		<?
			if($boolShowCopyright)
				echo "<p class=\"copyright\">&nbsp;".$strCopyright."<p/>";
			if($boolShowMnlfolioPromo)
				echo "<a href=\"http://ipositives.net/mnlfolio\" target=\"blank\"><img src=\"design/images/logo-mnlfolio-tiny.gif\" border=\"1\" /></a>";
			if($boolShowAdminLink)
				echo "<p><a class=\"copyright\" href=\"mnlfAdmin.php\">admin</a></p>";
		?>
		</td>
	</tr>
</table>


</body> 
</html>