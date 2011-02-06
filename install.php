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

if(isset($_REQUEST["frob"]))  {
?>

<p><b><u><? echo getResource("messageInstallComplete"); ?></u> :</b></p>
<p>
<? echo getResource("messageInstallCompleteDescription"); ?>
</p>

<?
}

else {
$currentUrl = "http".((!empty($_SERVER['HTTPS'])) ? "s" : "")."://".$_SERVER['SERVER_NAME'].((!empty($_SERVER["SERVER_PORT"])) ? ":".$_SERVER["SERVER_PORT"] : "").$_SERVER['REQUEST_URI'];
$callbackUrl = explode(".php",trim($currentUrl));
$callbackUrl = $callbackUrl[0].".php";
?>

<br />
<? echo getResource("messageInstallStep1Hello"); ?> <? echo $_SESSION["username"]; ?>!<br/><br/>

<b><? echo getResource("messageInstallStep1FolioNotRegistered"); ?></b><br/></br>

<b><? echo getResource("messageInstallStep1NeedCompletion"); ?></b><br/>

<hr/>

<?

if(getConf("ApiKey") == NULL && getConf("ApiSecret") == NULL) {

?>

<p><b><u><? echo getResource("titleStep"); ?> 1</u> :</b></p>
<p>
	<? echo getResource("messageInstallStep1InstructionsPart1"); ?><? echo "<a href=\"$urlApiKeyGenerator\" target=\"_blank\">"; ?><? echo getResource("messageInstallStep1InstructionsPart2"); ?>

	<table class="normal">
	 <tr>
	  <td><? echo getResource("messageInstallStep1Key"); ?> :</td><td><input type="text" size="50" name="ApiKey" /></td>
	 </tr>
	 <tr>
	  <td><? echo getResource("messageInstallStep1Secret"); ?> :</td><td><input type="text" size="50" name="ApiSecret" /></td>
	 </tr>
	</table>

</p>

<input type="submit" <? echo "value=\"".getResource("btnNext")."\""; ?>></td>

<?

} elseif(getConf("AuthToken") == NULL) {

	$Api_sig = md5(getConf("ApiSecret")."api_key".getConf("ApiKey")."perms".getConf("Perms"));
	$AuthUrl = "http://flickr.com/services/auth/?api_key=".getConf("ApiKey")."&perms=".getConf("Perms")."&api_sig=".$Api_sig;

?>

<p><b><u><? echo getResource("titleStep"); ?> 2</u> :</b></p>
<p>
<? echo getResource("messageInstallStep2InstructionsPart1"); ?>
<? echo "<p class=\"warning-left\">&nbsp;&nbsp;&nbsp;$callbackUrl</p>"; ?>
</p>

<p>
<? echo getResource("messageInstallStep2InstructionsPart2"); ?>
</p>

<p>
<? echo getResource("messageInstallStep2InstructionsPart3"); ?>
<? echo "<br /><br /><a href=\"".$AuthUrl."\">--> <u>".getResource("messageInstallStep2InstructionsPart4")."</u> <--</a>"; ?>
</p>

<?
}
}
?>
<hr/>

		
