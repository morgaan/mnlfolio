<?php
/*
 *	mnlfolio v1.1.0
 *	by Morgan Cugerone - http://ipositives.net
 *	Last Modification: 20110216
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

if(isset($_REQUEST["frob"]))  {
	
	// If it is the install
	if(!getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {
	
		?>

		<p><b><u><? echo getResource("messageInstallComplete"); ?></u> :</b></p>
		<p>
		<? echo getResource("messageInstallCompleteDescription"); ?>
		</p>

		<?
	
	// If it is the account add	
	} else {

		echo "<p class=\"subtitle\" />".getResource("messageAddingAccount")."</p>";

		deleteTempConf("IsAddingExistingFlickrAccount");	
		
		?>

			<SCRIPT language="JavaScript">
			window.location="mnlfAdmin.php?view=sets";
			</SCRIPT>

		<?		
		
	}
}

else {
$currentUrl = "http".((!empty($_SERVER['HTTPS'])) ? "s" : "")."://".$_SERVER['SERVER_NAME'].((!empty($_SERVER["SERVER_PORT"])) ? ":".$_SERVER["SERVER_PORT"] : "").$_SERVER['REQUEST_URI'];
$callbackUrl = explode(".php",trim($currentUrl));
$callbackUrl = $callbackUrl[0].".php";

	if(getTypedConf("AuthTokens") == NULL) {

?>

<br />
<? echo getResource("messageInstallStep1Hello"); ?> <? echo $_SESSION["username"]; ?>!<br/><br/>

<b><? echo getResource("messageInstallStep1FolioNotRegistered"); ?></b><br/></br>

<b><? echo getResource("messageInstallStep1NeedCompletion"); ?></b><br/>

<?

	} elseif(getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {
		
		echo "<p class=\"title\">".getResource("messageAddingExistingFlickrAccount")." : </p>";

	}

?>

<hr/>

<?

if(getTypedConf("ApiKey") == NULL && getTypedConf("ApiSecret") == NULL) {

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

<p class="button"><input type="submit" <? echo "value=\"".getResource("btnNext")."\""; ?>></p>

</td>

<?

} elseif(getTypedConf("AuthTokens") == NULL) {

	$Api_sig = md5(getTypedConf("ApiSecret")."api_key".getTypedConf("ApiKey")."perms".getTypedConf("Perms"));
	$AuthUrl = "http://flickr.com/services/auth/?api_key=".getTypedConf("ApiKey")."&perms=".getTypedConf("Perms")."&api_sig=".$Api_sig;

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
} elseif(getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {
	
	$Api_sig = md5(getTypedConf("ApiSecret")."api_key".getTypedConf("ApiKey")."perms".getTypedConf("Perms"));
	$AuthUrl = "http://flickr.com/services/auth/?api_key=".getTypedConf("ApiKey")."&perms=".getTypedConf("Perms")."&api_sig=".$Api_sig;
	
?>
	<p>
	<? echo getResource("messageAddAccountInstructionsPart1"); ?>
	<? echo "<p class=\"warning-left\">&nbsp;&nbsp;&nbsp;$callbackUrl</p>"; ?>
	</p>

	<p>
	<? echo getResource("messageAddAccountInstructionsPart2"); ?>
	</p>

	<p>
	<? echo getResource("messageAddAccountInstructionsPart3"); ?>
	<? echo "<br /><br /><a href=\"".$AuthUrl."\">--> <u>".getResource("messageAddAccountInstructionsPart4")."</u> <--</a>"; ?>
	</p>		

	<input type="hidden" name="addAnotherAccountCancel" value="false" />
	<p class="button"><input type="button" <? echo "value=\"".getResource("btnCancel")."\""; ?> onClick="javascript:this.form.addAnotherAccountCancel.value=true;this.form.submit();">		
<?		
}
}
?>
<hr/>

		
