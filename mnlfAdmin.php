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

session_start();

require("mnlfLib.php");
include("config/mnlfConfig.php");

foreach($_REQUEST as $key => $value) $$key=$value;

// ======================================
// = Tells if the user is authenticated =
// ======================================
function isUserAuthenticated() {
	return (isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["username"] == getConf("Username") && md5($_SESSION["password"]) == getConf("Password"));
}

// ======================================
// = Tells if config needs to be saved  =
// ======================================
function isConfigToSave() {
	return (isUserAuthenticated() && ((isset($view) && $view == "config") || !isset($view)));
}

// ============================
// = Handling language change =
// ============================
if(isset($_POST["lang"]) || (isset($_POST["Language"]) && isset($_SESSION["lang"]) && $_POST["Language"] != $_SESSION["lang"])) {
	if(isset($_SESSION["rsrc"]))
		unset($_SESSION["rsrc"]);
	if(isset($_POST["lang"])) {	
		$_SESSION["lang"] = $_POST["lang"];
		setConf("Language",$_POST["lang"]);
	}
	elseif(isset($_POST["Language"])) {
		$_SESSION["lang"] = $_POST["Language"];
		setConf("Language",$_POST["Language"]);
	}
}

// ======================
// = Init admin account =
// ======================
if(isset($_POST["password2"]) && isset($_POST["password"]) && isset($_POST["username"]) && strlen($_POST["password2"]) > 0 && strlen($_POST["password"]) > 0 && strlen($_POST["username"]) > 0) {
	
	if($_POST["password2"] != $_POST["password"]) {
?>
			<p class="warning"><? echo getResource("messagePasswordsMismatch"); ?></p>
<?
	} else {
		
		// init admin account credentials
		setConf("Username",$_POST["username"]);
		setConf("Password",md5($_POST["password"]), true);
		setConf("PwdQuestion",$_POST["pwdQuestion"]);
		setConf("PwdAnswer",md5($_POST["pwdAnswer"]), true);
		$strUsername = getConf("Username");
		$strPassword = getConf("Password");
		$_SESSION["username"] = $strUsername;
		$_SESSION["password"] = $_POST["password"];
		
	}
	
}

// ===========================
// = Reinit Password Process =
// ===========================
if(isset($_POST["pwdRenewPassword2"]) && isset($_POST["pwdRenewPassword"]) && isset($_POST["pwdRenewAnswer"]) && strlen($_POST["pwdRenewPassword2"]) > 0 && strlen($_POST["pwdRenewPassword"]) > 0 && strlen($_POST["pwdRenewAnswer"]) > 0) {

	if(md5($_POST["pwdRenewAnswer"]) == getConf("PwdAnswer")) {
		
		if($_POST["pwdRenewPassword2"] != $_POST["pwdRenewPassword"]) {
		  $_POST["pwdRenew"]="true";
?>
			<p class="warning"><? echo getResource("messagePasswordsMismatch"); ?></p>
<?
		} else {

			// set admin password
			setConf("Password",md5($_POST["pwdRenewPassword"]), true);

?>
			<p class="succeed"><? echo getResource("messagePasswordChanged"); ?></p>		
<?
		}		
		
	} else {
			$_POST["pwdRenew"]="true";
?>
			<p class="warning"><? echo getResource("messagePwdAnswerMismatch"); ?></p>
<?		
		
	}

}

// ==========================
// = Authentication process =
// ==========================
if(isset($_POST["password"]) && isset($_POST["username"]) && strlen($_POST["password"]) > 0 && strlen($_POST["username"]) > 0) {
	
	if($_POST["username"] == $strUsername) {
		
		if(md5($_POST["password"]) == $strPassword) {
		
			$_SESSION["username"] = $_POST["username"];
			$_SESSION["password"] = $_POST["password"];

		} else {
			
			?>
				<p class="warning"><? echo getResource("messageWrongPassword"); ?></p>
			<?			
			
		}
		
		
	}

}

// ============
// = Sign out =
// ============
if(isset($_SESSION["username"]) && isset($_POST["signout"]) && $_POST["signout"] == "true") {
	ob_start();
	cleanSession();
	header("Location: mnlfAdmin.php");
	ob_flush();
}

// =========================
// = Save config if needed =
// =========================
if(isConfigToSave())
	saveConf();

if(isUserAuthenticated()) {

	// If explicitly asked, reset all the mnlfolio
	if(isset($_POST["resetAll"]) && $_POST["resetAll"] == "true") {
	
		ob_start();
		resetCache();
		setConf("ApiKey", "");
		setConf("ApiSecret", "");
		setConf("AuthToken", "");
		setConf("SelectedSets", "");
		setConf("DefaultSet", "");
		copy ("default/css.php", "config/css.php");
		copy ("default/mnlfConfig.php", "config/mnlfConfig.php");
		cleanSession();
		header("Location: index.php");
		ob_flush();
	
	}

	// If explicitly asked, reset flickr connectivity
	if(isset($_POST["resetFlickr"]) && $_POST["resetFlickr"] == "true") {
	
		ob_start();
		resetCache();
		setConf("ApiKey", "");
		setConf("ApiSecret", "");
		setConf("AuthToken", "");
		header("Location: mnlfAdmin.php");
		ob_flush();
	
	}

	// On callback from Flickr, update the token
	if(isset($_REQUEST["frob"]) && 	getConf("AuthToken") == NULL)  {
		setFrob($_REQUEST["frob"]);
	
	}


	// Purge cache if explicitly asked
	if(isset($_POST["resetCache"]) && $_POST["resetCache"] == "true") {
		resetCache();
	}


	// If asked, add a set
	if(isset($_POST["addSets"]) && $_POST["addSets"] == "true" && isset($_POST["unSelectedSets"])) {
	
		$newSelected = getConf("SelectedSets");
		foreach ($_POST["unSelectedSets"] as $unselected) {
			if(substr_count($newSelected,$unselected) == 0) 
			 if($newSelected == NULL)
				$newSelected .="$unselected";
			 else
				$newSelected .=",$unselected";
		}
		setConf("SelectedSets", $newSelected);
	
	}

	// If asked, remove from set
	if(isset($_POST["removeSets"]) && $_POST["removeSets"] == "true" && isset($_POST["selectedSets"])) {
	
		$newSelected = getConf("SelectedSets");
		foreach ($_POST["selectedSets"] as $selected) {
			if(substr_count($newSelected,",".$selected) > 0) 
				$newSelected = str_replace(",".$selected,"",$newSelected);
			elseif(substr_count($newSelected,$selected) > 0) 
				$newSelected = str_replace($selected,"",$newSelected);
		}
		setConf("SelectedSets", $newSelected);
	
	}

	// If asked, set selected set as default
	if(isset($_POST["clearDefault"]) && $_POST["clearDefault"] == "true")
		setConf("DefaultSet", "");


	// If asked, set selected set as default
	if(isset($_POST["setDefault"]) && $_POST["setDefault"] == "true" && isset($_POST["selectedSets"])) {
	
		$newDefault = getConf("DefaultSet");
		foreach ($_POST["selectedSets"] as $selected) {
			$newDefault = $selected;
			break;
		}
		setConf("DefaultSet", $newDefault);
	
	}

	// If asked, upload a file
	if(isset($_POST["uploadFile"]) && $_POST["uploadFile"] == "true" && isset($_FILES["uploadedFile"]) && isset($_POST["targetDirectory"])) {
	
		$target_path = $_POST["targetDirectory"]."/".basename($_FILES['uploadedFile']['name']);
		move_uploaded_file($_FILES['uploadedFile']['tmp_name'], $target_path);

	}

	// If asked, delete file(s)
	if(isset($_POST["removeFile"]) && $_POST["removeFile"] == "true" && isset($_POST["selectedFiles"]) && isset($_POST["targetDirectory"])) {

		foreach ($_POST["selectedFiles"] as $selected) {
			unlink($_POST["targetDirectory"]."/".$selected) or DIE("couldn't delete ".$_POST["targetDirectory"]."/".$selected."<br />");
		}

	}


	// Set the submitted CSS conf
	if(isset($_POST["mnlfbodybgcolor"])
		&& isset($_POST["mnlfbodyfontfamily"])
		&& isset($_POST["mnlfbodyfontsize"])
		&& isset($_POST["mnlfbodyfontweight"])
		&& isset($_POST["mnlfbodyfontstyle"])
		&& isset($_POST["mnlfbodytextdecoration"])
		&& isset($_POST["mnlfbodytextalign"])
		&& isset($_POST["mnlfbodyfontcolor"])

		&& isset($_POST["mnlflinksfontfamily"])
		&& isset($_POST["mnlflinksfontsize"])
		&& isset($_POST["mnlflinksfontweight"])
		&& isset($_POST["mnlflinksfontstyle"])
		&& isset($_POST["mnlflinkstextdecoration"])
		&& isset($_POST["mnlflinkstextalign"])
		&& isset($_POST["mnlflinksfontcolor"])

		&& isset($_POST["mnlfcontactfontfamily"])
		&& isset($_POST["mnlfcontactfontsize"])
		&& isset($_POST["mnlfcontactfontweight"])
		&& isset($_POST["mnlfcontactfontstyle"])
		&& isset($_POST["mnlfcontacttextdecoration"])
		&& isset($_POST["mnlfcontacttextalign"])
		&& isset($_POST["mnlfcontactfontcolor"])
		
		&& isset($_POST["mnlfcopyrightfontfamily"])
		&& isset($_POST["mnlfcopyrightfontsize"])
		&& isset($_POST["mnlfcopyrightfontweight"])
		&& isset($_POST["mnlfcopyrightfontstyle"])
		&& isset($_POST["mnlfcopyrighttextdecoration"])
		&& isset($_POST["mnlfcopyrighttextalign"])
		&& isset($_POST["mnlfcopyrightfontcolor"])

		&& isset($_POST["mnlfphototitlefontfamily"])
		&& isset($_POST["mnlfphototitlefontsize"])
		&& isset($_POST["mnlfphototitlefontweight"])
		&& isset($_POST["mnlfphototitlefontstyle"])
		&& isset($_POST["mnlfphototitletextdecoration"])
		&& isset($_POST["mnlfphototitletextalign"])
		&& isset($_POST["mnlfphototitlefontcolor"])
	
		&& isset($_POST["mnlfphotodescriptionfontfamily"])
		&& isset($_POST["mnlfphotodescriptionfontsize"])
		&& isset($_POST["mnlfphotodescriptionfontweight"])
		&& isset($_POST["mnlfphotodescriptionfontstyle"])
		&& isset($_POST["mnlfphotodescriptiontextdecoration"])
		&& isset($_POST["mnlfphotodescriptiontextalign"])
		&& isset($_POST["mnlfphotodescriptionfontcolor"])

		&& isset($_POST["mnlftdthumbborderwidth"])
		&& isset($_POST["mnlftdthumbborderstyle"])
		&& isset($_POST["mnlftdthumbbordercolor"])

		&& isset($_POST["mnlftdthumbselectedborderwidth"])
		&& isset($_POST["mnlftdthumbselectedborderstyle"])
		&& isset($_POST["mnlftdthumbselectedbordercolor"])

		&& isset($_POST["mnlfimgthumbborderwidth"])
		&& isset($_POST["mnlfimgthumbborderstyle"])
		&& isset($_POST["mnlfimgthumbbordercolor"])
		&& isset($_POST["mnlfimgthumbopacity"])

		&& isset($_POST["mnlfimgthumbselectedborderwidth"])
		&& isset($_POST["mnlfimgthumbselectedborderstyle"])
		&& isset($_POST["mnlfimgthumbselectedbordercolor"])
		&& isset($_POST["mnlfimgthumbselectedopacity"])

		&& isset($_POST["mnlfimgthumbhoverborderwidth"])
		&& isset($_POST["mnlfimgthumbhoverborderstyle"])
		&& isset($_POST["mnlfimgthumbhoverbordercolor"])
		&& isset($_POST["mnlfimgthumbhoveropacity"])

		&& isset($_POST["mnlfimgphotoborderwidth"])
		&& isset($_POST["mnlfimgphotoborderstyle"])
		&& isset($_POST["mnlfimgphotobordercolor"])

		&& isset($_POST["mnlfdivphotoborderwidth"])
		&& isset($_POST["mnlfdivphotoborderstyle"])
		&& isset($_POST["mnlfdivphotobordercolor"])) {
		
			setCSSConf("unSelectedSets",$_POST["unSelectedSets"]);
			setCSSConf("mnlfbodybgcolor",$_POST["mnlfbodybgcolor"]);
			setCSSConf("mnlfbodyfontfamily",$_POST["mnlfbodyfontfamily"]);
			setCSSConf("mnlfbodyfontsize",$_POST["mnlfbodyfontsize"]);
			setCSSConf("mnlfbodyfontweight",$_POST["mnlfbodyfontweight"]);
			setCSSConf("mnlfbodyfontstyle",$_POST["mnlfbodyfontweight"]);
			setCSSConf("mnlfbodytextdecoration",$_POST["mnlfbodytextdecoration"]);
			setCSSConf("mnlfbodytextalign",$_POST["mnlfbodytextalign"]);
			setCSSConf("mnlfbodyfontcolor",$_POST["mnlfbodyfontcolor"]);

			setCSSConf("mnlflinksfontfamily",$_POST["mnlflinksfontfamily"]);
			setCSSConf("mnlflinksfontsize",$_POST["mnlflinksfontsize"]);
			setCSSConf("mnlflinksfontweight",$_POST["mnlflinksfontweight"]);
			setCSSConf("mnlflinksfontstyle",$_POST["mnlflinksfontstyle"]);
			setCSSConf("mnlflinkstextdecoration",$_POST["mnlflinkstextdecoration"]);
			setCSSConf("mnlflinkstextalign",$_POST["mnlflinkstextalign"]);
			setCSSConf("mnlflinksfontcolor",$_POST["mnlflinksfontcolor"]);

			setCSSConf("mnlfcontactfontfamily",$_POST["mnlfcontactfontfamily"]);
			setCSSConf("mnlfcontactfontsize",$_POST["mnlfcontactfontsize"]);
			setCSSConf("mnlfcontactfontweight",$_POST["mnlfcontactfontweight"]);
			setCSSConf("mnlfcontactfontstyle",$_POST["mnlfcontactfontstyle"]);
			setCSSConf("mnlfcontacttextdecoration",$_POST["mnlfcontacttextdecoration"]);
			setCSSConf("mnlfcontacttextalign",$_POST["mnlfcontacttextalign"]);
			setCSSConf("mnlfcontactfontcolor",$_POST["mnlfcontactfontcolor"]);
			
			setCSSConf("mnlfcopyrightfontfamily",$_POST["mnlfcopyrightfontfamily"]);
			setCSSConf("mnlfcopyrightfontsize",$_POST["mnlfcopyrightfontsize"]);
			setCSSConf("mnlfcopyrightfontweight",$_POST["mnlfcopyrightfontweight"]);
			setCSSConf("mnlfcopyrightfontstyle",$_POST["mnlfcopyrightfontstyle"]);
			setCSSConf("mnlfcopyrighttextdecoration",$_POST["mnlfcopyrighttextdecoration"]);
			setCSSConf("mnlfcopyrighttextalign",$_POST["mnlfcopyrighttextalign"]);
			setCSSConf("mnlfcopyrightfontcolor",$_POST["mnlfcopyrightfontcolor"]);

			setCSSConf("mnlfphototitlefontfamily",$_POST["mnlfphototitlefontfamily"]);
			setCSSConf("mnlfphototitlefontsize",$_POST["mnlfphototitlefontsize"]);
			setCSSConf("mnlfphototitlefontweight",$_POST["mnlfphototitlefontweight"]);
			setCSSConf("mnlfphototitlefontstyle",$_POST["mnlfphototitlefontstyle"]);
			setCSSConf("mnlfphototitletextdecoration",$_POST["mnlfphototitletextdecoration"]);
			setCSSConf("mnlfphototitletextalign",$_POST["mnlfphototitletextalign"]);
			setCSSConf("mnlfphototitlefontcolor",$_POST["mnlfphototitlefontcolor"]);
		
			setCSSConf("mnlfphotodescriptionfontfamily",$_POST["mnlfphotodescriptionfontfamily"]);
			setCSSConf("mnlfphotodescriptionfontsize",$_POST["mnlfphotodescriptionfontsize"]);
			setCSSConf("mnlfphotodescriptionfontweight",$_POST["mnlfphotodescriptionfontweight"]);
			setCSSConf("mnlfphotodescriptionfontstyle",$_POST["mnlfphotodescriptionfontstyle"]);
			setCSSConf("mnlfphotodescriptiontextdecoration",$_POST["mnlfphotodescriptiontextdecoration"]);
			setCSSConf("mnlfphotodescriptiontextalign",$_POST["mnlfphotodescriptiontextalign"]);
			setCSSConf("mnlfphotodescriptionfontcolor",$_POST["mnlfphotodescriptionfontcolor"]);

			setCSSConf("mnlftdthumbborderwidth",$_POST["mnlftdthumbborderwidth"]);
			setCSSConf("mnlftdthumbborderstyle",$_POST["mnlftdthumbborderstyle"]);
			setCSSConf("mnlftdthumbbordercolor",$_POST["mnlftdthumbbordercolor"]);

			setCSSConf("mnlftdthumbselectedborderwidth",$_POST["mnlftdthumbselectedborderwidth"]);
			setCSSConf("mnlftdthumbselectedborderstyle",$_POST["mnlftdthumbselectedborderstyle"]);
			setCSSConf("mnlftdthumbselectedbordercolor",$_POST["mnlftdthumbselectedbordercolor"]);

			setCSSConf("mnlfimgthumbborderwidth",$_POST["mnlfimgthumbborderwidth"]);
			setCSSConf("mnlfimgthumbborderstyle",$_POST["mnlfimgthumbborderstyle"]);
			setCSSConf("mnlfimgthumbbordercolor",$_POST["mnlfimgthumbbordercolor"]);
			setCSSConf("mnlfimgthumbopacity",$_POST["mnlfimgthumbopacity"]);

			setCSSConf("mnlfimgthumbselectedborderwidth",$_POST["mnlfimgthumbselectedborderwidth"]);
			setCSSConf("mnlfimgthumbselectedborderstyle",$_POST["mnlfimgthumbselectedborderstyle"]);
			setCSSConf("mnlfimgthumbselectedbordercolor",$_POST["mnlfimgthumbselectedbordercolor"]);
			setCSSConf("mnlfimgthumbselectedopacity",$_POST["mnlfimgthumbselectedopacity"]);

			setCSSConf("mnlfimgthumbhoverborderwidth",$_POST["mnlfimgthumbhoverborderwidth"]);
			setCSSConf("mnlfimgthumbhoverborderstyle",$_POST["mnlfimgthumbhoverborderstyle"]);
			setCSSConf("mnlfimgthumbhoverbordercolor",$_POST["mnlfimgthumbhoverbordercolor"]);
			setCSSConf("mnlfimgthumbhoveropacity",$_POST["mnlfimgthumbhoveropacity"]);

			setCSSConf("mnlfimgphotoborderwidth",$_POST["mnlfimgphotoborderwidth"]);
			setCSSConf("mnlfimgphotoborderstyle",$_POST["mnlfimgphotoborderstyle"]);
			setCSSConf("mnlfimgphotobordercolor",$_POST["mnlfimgphotobordercolor"]);

			setCSSConf("mnlfdivphotoborderwidth",$_POST["mnlfdivphotoborderwidth"]);
			setCSSConf("mnlfdivphotoborderstyle",$_POST["mnlfdivphotoborderstyle"]);
			setCSSConf("mnlfdivphotobordercolor",$_POST["mnlfdivphotobordercolor"]);

	}
}

?>

<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title><? echo $strPageTitle; ?> admin</title>
	<link rel="stylesheet" href="design/css/mnlfadmin.css" type="text/css" media="screen" title="styles" charset="utf-8">
</head>

<body <? if(isset($view) && $view == "css") echo "onLoad=\"javascript:UpdatePreview();\""  ?>>	
	<script type="text/javascript" src="design/jscolor/jscolor.js"></script>

	<form id="mnlform" <? if(isset($view) && ($view == "layouts" || $view == "logos")) echo "enctype=\"multipart/form-data\""; ?>  <? if(!isset($view)) echo "action=\"mnlfAdmin.php\""; else echo "action=\"mnlfAdmin.php?view=".$view."\""; ?>  method="post">

<?

// If asked, display password renew page
if(isset($_POST["pwdRenew"]) && $_POST["pwdRenew"] == "true") {
	unset($_SESSION["username"]);
	unset($_SESSION["password"]);
?>
	<table border="0" class="normal" align="center">
		<tr>
			<td><? echo getResource("pwdQuestion"); ?> :</td>
			<td><? echo getConf("PwdQuestion"); ?> ?</td>
		</tr>
		<tr>
			<td><? echo getResource("pwdAnswer"); ?> :</td>
			<td><input type="password" name="pwdRenewAnswer" size="60" /></td>
		</tr>
		<tr>
			<td><? echo getResource("password"); ?> :</td>
			<td><input type="password" name="pwdRenewPassword" /></td>
		</tr>
		<tr>
			<td><? echo getResource("password2"); ?> :</td>
			<td><input type="password" name="pwdRenewPassword2" /></td>
		</tr>
	</table>
<?
}


if(getConf("Username") == NULL) {
?>
	<p class="warning"><? echo getResource("messageFolioNotInit"); ?></p>
<?
}

if(!isset($_SESSION["username"]) && !(isset($_POST["pwdRenew"]) && $_POST["pwdRenew"] == "true")) {
?>

	<table border="0" class="normal" align="center">
		<tr>
			<td><? echo getResource("language"); ?> :</td>
			<td>
<?
				$selected = getListSelectedValue("Language");
?>
				<select name="lang" onChange="javascript:this.form.submit();">
<?
				foreach(getListValues("Language") as $language) {
					if($language == $selected)
						echo "<option value=\"$language\" selected>$language</option>";
					else
						echo "<option value=\"$language\">$language</option>";
				}
?>		
				</select>
			</td>
		</tr>
			
		<tr>
			<td><? echo getResource("username"); ?> :</td>
			<td><input type="text" name="username" /></td>
		</tr>
		<tr>
			<td><? echo getResource("password"); ?> :</td>
			<td><input type="password" name="password" /></td>
		</tr>

<?

		if(getConf("Username") != NULL) {
?>
					<tr>
						<td colspan="2" align="right" style="font-size:10px;font-color:#0f0;font-style:italic;">
							<input type="hidden" name="pwdRenew" value="false" />
							<a href="javascript:mnlform.pwdRenew.value=true;mnlform.submit();"><? echo getResource("lostPassword"); ?></a>
						</td>
					</tr>

			<?
			
		}

}

if(!isset($_SESSION["username"]) && getConf("Username") == NULL) {
?>
		<tr>
			<td><? echo getResource("password2"); ?> :</td>
			<td><input type="password" name="password2" /></td>
		</tr>
		<tr>
			<td><? echo getResource("pwdQuestion"); ?> :</td>
			<td><input type="text" name="pwdQuestion" size="60" /></td>
		</tr>
		<tr>
			<td><? echo getResource("pwdAnswer"); ?> :</td>
			<td><input type="password" name="pwdAnswer" size="60" /></td>
		</tr>
				
<?
}

if(isUserAuthenticated()) {
?>

		<input type="hidden" name="signout" value="false" />

		<ul id="admintab">
				     
			<li <? if(!isset($view) && !isset($_REQUEST["frob"])) echo "class=\"active\""; ?>><a href="mnlfAdmin.php"><? echo getResource("tabLabelConfiguration"); ?></a></li>
<?
		if(getConf("AuthToken") != NULL) {
?>
		     <li <? if(isset($view) && $view == "css") echo "class=\"active\""; ?>><a href="mnlfAdmin.php?view=css"><? echo getResource("tabLabelAppearance"); ?></a></li>
	     	 <li <? if(isset($view) && $view == "layouts") echo "class=\"active\""; ?>><a href="mnlfAdmin.php?view=layouts"><? echo getResource("tabLayouts"); ?></a></li>
	     	 <li <? if(isset($view) && $view == "logos") echo "class=\"active\""; ?>><a href="mnlfAdmin.php?view=logos"><? echo getResource("tabLogos"); ?></a></li>
		     <li <? if(isset($view) && $view == "sets") echo "class=\"active\""; ?>><a href="mnlfAdmin.php?view=sets"><? echo getResource("tabLabelMySets"); ?></a></li>
<?
		}

		if(getConf("AuthToken") != NULL) {
?>
		     <li><a href="index.php"><? echo getResource("tabLabelSeeFolio"); ?></a></li>
<?
		}
?>
	 <li <? if(isset($view) && $view == "more") echo "class=\"active\""; else echo "class=\"more\""; ?>><a href="mnlfAdmin.php?view=more"><? echo getResource("tabLabelMore"); ?></a></li>

			 <li class="signout"><input type="button" onClick="javascript:this.form.signout.value=true;this.form.submit();" <? echo "value=\"".getResource("btnSignOut")."\""; ?> /></li>

		</ul>
<?
if(!((getConf("ApiKey") == NULL || getConf("ApiSecret") == NULL || getConf("AuthToken") == NULL || isset($_REQUEST["frob"])))) {
?>		
		<center>
<?
}
?>
		<table border="0" class="normal">
	<?
		if((getConf("ApiKey") == NULL || getConf("ApiSecret") == NULL || getConf("AuthToken") == NULL || isset($_REQUEST["frob"])))		
			include("install.php");


		if(!isset($view) && getConf("ApiKey") != NULL && getConf("ApiSecret") != NULL && getConf("ApiSecret") != NULL && getConf("AuthToken") != NULL && !isset($_REQUEST["frob"]))
			getFormConf();

		elseif(isset($view) && $view == "layouts" && (getConf("ApiKey") != NULL && getConf("ApiSecret") != NULL && getConf("AuthToken") != NULL)) {
			getUploader("design/layouts/navigation", "", getResource("navigation"), getResource("messageUploadNavigationLayout"), getResource("btnUpload"), getResource("messageDeleteNavigationLayout"));
			getUploader("design/layouts/viewer", "", getResource("layout"), getResource("messageUploadViewerLayout"), getResource("btnUpload"), getResource("messageDeleteViewerLayout"));
		}

		elseif(isset($view) && $view == "logos" && (getConf("ApiKey") != NULL && getConf("ApiSecret") != NULL && getConf("AuthToken") != NULL))
			getUploader("design/images/logos", "", getResource("logo"), getResource("messageUploadLogo"), getResource("btnUpload"), getResource("messageDeleteLogo"));
			
		elseif(isset($view) && $view == "sets" && (getConf("ApiKey") != NULL && getConf("ApiSecret") != NULL && getConf("AuthToken") != NULL)) {

?>			
				<br /><br />
				<input type="hidden" name="resetCache" value="false" />
				<center><input type="button" onClick="javascript:this.form.resetCache.value=true;this.form.submit();" <? echo "value=\"".getResource("btnResetCache")."\""; ?>  /></center>

<?
				getSetsSelector();
		}

		elseif(isset($view) && $view == "more")
			getMoreForm();

		
		elseif(isset($view) && $view == "css" && (getConf("ApiKey") != NULL && getConf("ApiSecret") != NULL && getConf("AuthToken") != NULL)) {
			include("design/css/cssgenerator.php");
		}
}

?>		
		</table>
		</center>
<?
		if(!isset($_SESSION["username"]) && !(isset($_POST["pwdRenew"]) && $_POST["pwdRenew"] == "true")) {
?>
		<center>
			<input type="submit" <? echo "value=\"".getResource("btnConnect")."\""; ?>>
		</center>
<?
		}

		if(isset($_POST["pwdRenew"]) && $_POST["pwdRenew"] == "true") {
?>
		<center>
			<input type="submit" <? echo "value=\"".getResource("btnRenewPwd")."\""; ?>>
		</center>
<?
		}
?>


		</form>
	</center>	

	<?
			echo "<p align=\"center\">v.".getConf("Version")."</p>";
	?>

</body>
</html>