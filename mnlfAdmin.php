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

session_start();

require("mnlfLib.php");
include("config/mnlfConfig.php");

foreach($_REQUEST as $key => $value) $$key=$value;

// ======================================
// = Tells if the user is authenticated =
// ======================================
function isUserAuthenticated() {
	return (isset($_SESSION["username"]) && isset($_SESSION["password"]) && $_SESSION["username"] == getTypedConf("Username") && md5($_SESSION["password"]) == getTypedConf("Password"));
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
		setTypedConf("Language",$_POST["lang"]);
	}
	elseif(isset($_POST["Language"])) {
		$_SESSION["lang"] = $_POST["Language"];
		setTypedConf("Language",$_POST["Language"]);
	}
}

// If asked, import config & apparence files
if(isset($_POST["importConfigFiles"]) && $_POST["importConfigFiles"] == "true") {
	
	if(isset($_FILES["configFile"]) && $_FILES["configFile"] != "") {
		
		$target_path = "config/".basename($_FILES['configFile']['name']).".old";
		move_uploaded_file($_FILES['configFile']['tmp_name'], $target_path);
		
		importConf($target_path,"config/mnlfConfig.php");		
		if(file_exists($target_path))
			unlink($target_path);
		if(file_exists($target_path.".bak"))
			unlink($target_path.".bak");

	}
	if(isset($_FILES["appearanceFile"]) && $_FILES["appearanceFile"] != "") {
		$target_path = "config/".basename($_FILES['appearanceFile']['name']).".old";
		move_uploaded_file($_FILES['appearanceFile']['tmp_name'], $target_path);
		
		importConf($target_path,"config/css.php");
		if(file_exists($target_path))
			unlink($target_path);
		if(file_exists($target_path.".bak"))
			unlink($target_path.".bak");
	}
	if(isset($_FILES["headFile"]) && $_FILES["headFile"] != "") {

		if(file_exists("config/head.html"))
			unlink("config/head.html");
		move_uploaded_file($_FILES['headFile']['tmp_name'], "config/head.html");
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
		setTypedConf("Username",$_POST["username"]);
		setTypedConf("Password",md5($_POST["password"]), true);
		setTypedConf("PwdQuestion",$_POST["pwdQuestion"]);
		setTypedConf("PwdAnswer",md5($_POST["pwdAnswer"]), true);
		$strUsername = getTypedConf("Username");
		$strPassword = getTypedConf("Password");
		$_SESSION["username"] = $strUsername;
		$_SESSION["password"] = $_POST["password"];
		
	}
	
}

// ===========================
// = Reinit Password Process =
// ===========================
if(isset($_POST["pwdRenewPassword2"]) && isset($_POST["pwdRenewPassword"]) && isset($_POST["pwdRenewAnswer"]) && strlen($_POST["pwdRenewPassword2"]) > 0 && strlen($_POST["pwdRenewPassword"]) > 0 && strlen($_POST["pwdRenewAnswer"]) > 0) {

	if(md5($_POST["pwdRenewAnswer"]) == getTypedConf("PwdAnswer")) {
		
		if($_POST["pwdRenewPassword2"] != $_POST["pwdRenewPassword"]) {
		  $_POST["pwdRenew"]="true";
?>
			<p class="warning"><? echo getResource("messagePasswordsMismatch"); ?></p>
<?
		} else {

			// set admin password
			setTypedConf("Password",md5($_POST["pwdRenewPassword"]), true);

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
		reinitImagesCache();
		setTypedConf("ApiKey", "");
		setTypedConf("ApiSecret", "");
		setTypedConf("AuthTokens", "");
		setTypedConf("SelectedSets", "");
		reinitSetsCache();
		setTypedConf("DefaultSet", "");
		copy ("default/css.php", "config/css.php");
		copy ("default/mnlfConfig.php", "config/mnlfConfig.php");
		copy ("default/head.html", "config/head.html");
		if(file_exists("config/mnlfConfig.php.bak"))
			unlink("config/mnlfConfig.php.bak");
		if(file_exists("config/css.php.bak"))
			unlink("config/css.php.bak");
		cleanSession();
		header("Location: index.php");
		ob_flush();
	
	}

	// If explicitly asked, reset flickr connectivity
	if(isset($_POST["resetFlickr"]) && $_POST["resetFlickr"] == "true") {
	
		ob_start();
		reinitImagesCache();
		reinitSetsCache();
		setTypedConf("ApiKey", "");
		setTypedConf("ApiSecret", "");
		setTypedConf("AuthTokens", "");
		header("Location: mnlfAdmin.php");
		ob_flush();
	
	}

	// On callback from Flickr, update the token
	if((isset($_REQUEST["frob"]) && getTypedConf("AuthTokens") == NULL) || (isset($_REQUEST["frob"]) && getTypedConf("_TemporaryIsAddingExistingFlickrAccount")))  {
		setFrob($_REQUEST["frob"]);
	}


	// Purge images cache if explicitly asked
	if(isset($_POST["reinitImagesCache"]) && $_POST["reinitImagesCache"] == "true") {
		reinitImagesCache();
	}
	
	// Purge sets cache if explicitly asked
	if(isset($_POST["reinitSetsCache"]) && $_POST["reinitSetsCache"] == "true") {
		reinitSetsCache();
	}

	// Export configuration file if explicitly asked
	if(isset($_POST["exportConfig"]) && $_POST["exportConfig"] == "true") {
		exportFile("config/mnlfConfig.php");
	}

	// Export appearance file if explicitly asked
	if(isset($_POST["exportAppearance"]) && $_POST["exportAppearance"] == "true") {
		exportFile("config/css.php");
	}

	// Export header file if explicitly asked
	if(isset($_POST["exportHead"]) && $_POST["exportHead"] == "true") {
		exportFile("config/head.html");
	}

	// If asked, remove an account
	if(isset($_POST["removeAccount"]) && $_POST["removeAccount"] == "true" && isset($_POST["accountIndex"])) {
		
		// remove selected sets from this account
		$newSelected = getTypedConf("SelectedSets");
		$newselectedSets = null;
		$selectedSets = explode(",",$newSelected);

		for($i = 0; $i < count($selectedSets); $i++) {
			if(substr_count($selectedSets[$i],$_POST["accountIndex"].".") <= 0)
				$newselectedSets[] = $selectedSets[$i];
			else
				if(substr_count(getTypedConf("DefaultSet"),$selectedSets[$i]) > 0) 
					setTypedConf("DefaultSet", "");
		}
		
		$newSelected = "";
		for($i = 0; $i < count($newselectedSets); $i++) {
			if($i == 0)
				$newSelected .= $newselectedSets[$i];
			else
				$newSelected .= ",".$newselectedSets[$i];
		}

		setTypedConf("SelectedSets", $newSelected);
		reinitSetsCache();
		
		//remove account
		$newTokens = getTypedConf("AuthTokens");		
		$newAccounts = null;
		$existingTokens = explode("|",$newTokens);
		$tokenToBeRemoved = $existingTokens[$_POST["accountIndex"]];

		for($i = 0; $i < count($existingTokens); $i++) {
			if(substr_count($existingTokens[$i],$tokenToBeRemoved) <= 0)
				$newAccounts[] = $existingTokens[$i];
		}

		$newSelected = "";		
		for($i = 0; $i < count($newAccounts); $i++) {
			if($i == 0)
				$newSelected .= $newAccounts[$i];
			else
				$newSelected .= "|".$newAccounts[$i];
		}
		
		setTypedConf("AuthTokens", $newSelected);			

	}

	// If asked, add a set
	if(isset($_POST["addSets"]) && $_POST["addSets"] == "true" && isset($_POST["accountIndex"])) {
		
		if(isset($_POST["unSelectedSets".$_POST["accountIndex"]])) {
			$newSelected = getTypedConf("SelectedSets");
			foreach ($_POST["unSelectedSets".$_POST["accountIndex"]] as $unselected) {
				if(substr_count($newSelected,$_POST["accountIndex"].".".$unselected) == 0) 
				 if($newSelected == NULL)
					$newSelected .= $_POST["accountIndex"].".".$unselected;
				 else
					$newSelected .=",".$_POST["accountIndex"].".".$unselected;
			}
			setTypedConf("SelectedSets", $newSelected);
			reinitSetsCache();
		}
	
	}

	// If asked, remove from set
	if(isset($_POST["removeSets"]) && $_POST["removeSets"] == "true" && isset($_POST["selectedSets"])) {
	
		$newSelected = getTypedConf("SelectedSets");
		foreach ($_POST["selectedSets"] as $selected) {
			if(substr_count($selected,getTypedConf("DefaultSet")) > 0) 
				setTypedConf("DefaultSet", "");
			if(substr_count($newSelected,",".$selected) > 0) 
				$newSelected = str_replace(",".$selected,"",$newSelected);
			elseif(substr_count($newSelected,$selected) > 0) 
				$newSelected = str_replace($selected,"",$newSelected);
		}
		setTypedConf("SelectedSets", $newSelected);
		reinitSetsCache();
	
	}

	// If asked, move a set up
	if(isset($_POST["moveUp"]) && $_POST["moveUp"] == "true" && isset($_POST["selectedSets"])) {
	
		$newSelected = getTypedConf("SelectedSets");
		$selectedSet = $_POST["selectedSets"][0];
		$selectedSets = explode(",",$newSelected);
		$i = 0;
		for($i = 0; $i < count($selectedSets); $i++) {
			if($selectedSets[$i] == $selectedSet)
				break;
		}
		
		$selectedSets[$i] = $selectedSets[$i-1];
		$selectedSets[$i-1] = $selectedSet;
		
		$newSelected = "";
		for($i = 0; $i < count($selectedSets); $i++) {
			if($i == 0)
				$newSelected .= $selectedSets[$i];
			else
				$newSelected .= ",".$selectedSets[$i];
		}		

		setTypedConf("SelectedSets", $newSelected);
		reinitSetsCache();
	
	}

	// If asked, move a set down
	if(isset($_POST["moveDown"]) && $_POST["moveDown"] == "true" && isset($_POST["selectedSets"])) {
	
		$newSelected = getTypedConf("SelectedSets");
		$selectedSet = $_POST["selectedSets"][0];
		$selectedSets = explode(",",$newSelected);
		$i = 0;
		for($i = 0; $i < count($selectedSets); $i++) {
			if($selectedSets[$i] == $selectedSet)
				break;
		}
		
		$selectedSets[$i] = $selectedSets[$i+1];
		$selectedSets[$i+1] = $selectedSet;
		
		$newSelected = "";
		for($i = 0; $i < count($selectedSets); $i++) {
			if($i == 0)
				$newSelected .= $selectedSets[$i];
			else
				$newSelected .= ",".$selectedSets[$i];
		}		

		setTypedConf("SelectedSets", $newSelected);
		reinitSetsCache();
	
	}

	// If asked, set selected set as default
	if(isset($_POST["clearDefault"]) && $_POST["clearDefault"] == "true")
		setTypedConf("DefaultSet", "");


	// If asked, set selected set as default
	if(isset($_POST["setDefault"]) && $_POST["setDefault"] == "true" && isset($_POST["selectedSets"])) {
	
		$newDefault = getTypedConf("DefaultSet");
		foreach ($_POST["selectedSets"] as $selected) {
			$newDefault = $selected;
			break;
		}
		setTypedConf("DefaultSet", $newDefault);
	
	}
	
	
	// If asked, upload a file
	if(isset($_POST["uploadFileLogo"]) && $_POST["uploadFileLogo"] == "true" && isset($_FILES["uploadedFileLogo"]) && isset($_POST["targetDirectoryLogo"])) {
		$target_path = $_POST["targetDirectoryLogo"]."/".basename($_FILES['uploadedFileLogo']['name']);
		$uploaded =  move_uploaded_file($_FILES['uploadedFileLogo']['tmp_name'], $target_path);
	}
	elseif (isset($_POST["uploadFileFont"]) && $_POST["uploadFileFont"] == "true" && isset($_FILES["uploadedFileFont"]) && isset($_POST["targetDirectoryFont"])) {
		$font_name = "";
		foreach ($_FILES['uploadedFileFont']['name'] as $filename) {
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$font_name = basename($filename, ".".$ext);
			break;
		}
		$target_folder = $_POST["targetDirectoryFont"]."/".$font_name;
		if (!file_exists($target_folder)) {
			mkdir($target_folder, 0777);
		}
		$count=0;
        foreach ($_FILES['uploadedFileFont']['name'] as $filename) {
        	$tmpFilePath = $_FILES['uploadedFileFont']['tmp_name'][$count];
        	$ext = pathinfo($_FILES['uploadedFileFont']['name'][$count], PATHINFO_EXTENSION);
			$target_path = $target_folder."/".$font_name.".".$ext;
			$uploaded =  move_uploaded_file($tmpFilePath, $target_path);
			$count++;
        }
	} elseif(isset($_POST["uploadFileViewerLayout"]) && $_POST["uploadFileViewerLayout"] == "true" && isset($_FILES["uploadedFileViewerLayout"]) && isset($_POST["targetDirectoryViewerLayout"])) {
		$target_path = $_POST["targetDirectoryViewerLayout"]."/".basename($_FILES['uploadedFileViewerLayout']['name']);
		$uploaded =  move_uploaded_file($_FILES['uploadedFileViewerLayout']['tmp_name'], $target_path);
	}
	elseif(isset($_POST["uploadFileNavigationLayout"]) && $_POST["uploadFileNavigationLayout"] == "true" && isset($_FILES["uploadedFileNavigationLayout"]) && isset($_POST["targetDirectoryNavigationLayout"])) {
		$target_path = $_POST["targetDirectoryNavigationLayout"]."/".basename($_FILES['uploadedFileNavigationLayout']['name']);
		$uploaded =  move_uploaded_file($_FILES['uploadedFileNavigationLayout']['tmp_name'], $target_path);
	}
	
	// If asked, delete file(s)
	if(isset($_POST["removeFileLogo"]) && $_POST["removeFileLogo"] == "true" && isset($_POST["selectedFilesLogo"]) && isset($_POST["targetDirectoryLogo"])) {
		foreach ($_POST["selectedFilesLogo"] as $selected) {
			if(file_exists($_POST["targetDirectoryLogo"]."/".$selected))
				unlink($_POST["targetDirectoryLogo"]."/".$selected) or DIE("couldn't delete ".$_POST["targetDirectoryLogo"]."/".$selected."<br />");
		}
	} elseif(isset($_POST["removeFileFont"]) && $_POST["removeFileFont"] == "true" && isset($_POST["selectedFilesFont"]) && isset($_POST["targetDirectoryFont"])) {
		foreach ($_POST["selectedFilesFont"] as $selected) {
			$dir = $_POST["targetDirectoryFont"]."/".$selected;
			if(file_exists($dir) && is_dir($dir)) {
				rrmdir($dir);
    		}
    	}
	} elseif(isset($_POST["removeFileNavigationLayout"]) && $_POST["removeFileNavigationLayout"] == "true" && isset($_POST["selectedFilesNavigationLayout"]) && isset($_POST["targetDirectoryNavigationLayout"])) {
		foreach ($_POST["selectedFilesNavigationLayout"] as $selected) {
			if(file_exists($_POST["targetDirectoryNavigationLayout"]."/".$selected))
				unlink($_POST["targetDirectoryNavigationLayout"]."/".$selected) or DIE("couldn't delete ".$_POST["targetDirectoryNavigationLayout"]."/".$selected."<br />");
		}
	} elseif(isset($_POST["removeFileViewerLayout"]) && $_POST["removeFileViewerLayout"] == "true" && isset($_POST["selectedFilesViewerLayout"]) && isset($_POST["targetDirectoryViewerLayout"])) {
		foreach ($_POST["selectedFilesViewerLayout"] as $selected) {
			if(file_exists($_POST["targetDirectoryViewerLayout"]."/".$selected))
				unlink($_POST["targetDirectoryViewerLayout"]."/".$selected) or DIE("couldn't delete ".$_POST["targetDirectoryViewerLayout"]."/".$selected."<br />");
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

		&& isset($_POST["mnlflogofontfamily"])
		&& isset($_POST["mnlflogofontsize"])
		&& isset($_POST["mnlflogofontweight"])
		&& isset($_POST["mnlflogofontstyle"])
		&& isset($_POST["mnlflogotextdecoration"])
		&& isset($_POST["mnlflogotextalign"])
		&& isset($_POST["mnlflogofontcolor"])
		
		&& isset($_POST["mnlftitlefontfamily"])
		&& isset($_POST["mnlftitlefontsize"])
		&& isset($_POST["mnlftitlefontweight"])
		&& isset($_POST["mnlftitlefontstyle"])
		&& isset($_POST["mnlftitletextdecoration"])
		&& isset($_POST["mnlftitletextalign"])
		&& isset($_POST["mnlftitlefontcolor"])
		
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

		&& isset($_POST["mnlfphotonavigationcontrolsfontfamily"])
		&& isset($_POST["mnlfphotonavigationcontrolsfontsize"])
		&& isset($_POST["mnlfphotonavigationcontrolsfontweight"])
		&& isset($_POST["mnlfphotonavigationcontrolsfontstyle"])
		&& isset($_POST["mnlfphotonavigationcontrolstextdecoration"])
		&& isset($_POST["mnlfphotonavigationcontrolsfontcolor"])

		&& isset($_POST["mnlfthumbnailsnavigationcontrolsfontfamily"])
		&& isset($_POST["mnlfthumbnailsnavigationcontrolsfontsize"])
		&& isset($_POST["mnlfthumbnailsnavigationcontrolsfontweight"])
		&& isset($_POST["mnlfthumbnailsnavigationcontrolsfontstyle"])
		&& isset($_POST["mnlfthumbnailsnavigationcontrolstextdecoration"])
		&& isset($_POST["mnlfthumbnailsnavigationcontrolsfontcolor"])		
		
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
		
			setConf("mnlfbodybgcolor",$_POST["mnlfbodybgcolor"]);
			setConf("mnlfbodyfontfamily",$_POST["mnlfbodyfontfamily"]);
			setConf("mnlfbodyfontsize",$_POST["mnlfbodyfontsize"]);
			setConf("mnlfbodyfontweight",$_POST["mnlfbodyfontweight"]);
			setConf("mnlfbodyfontstyle",$_POST["mnlfbodyfontweight"]);
			setConf("mnlfbodytextdecoration",$_POST["mnlfbodytextdecoration"]);
			setConf("mnlfbodytextalign",$_POST["mnlfbodytextalign"]);
			setConf("mnlfbodyfontcolor",$_POST["mnlfbodyfontcolor"]);

			setConf("mnlflogofontfamily",$_POST["mnlflogofontfamily"]);
			setConf("mnlflogofontsize",$_POST["mnlflogofontsize"]);
			setConf("mnlflogofontweight",$_POST["mnlflogofontweight"]);
			setConf("mnlflogofontstyle",$_POST["mnlflogofontstyle"]);
			setConf("mnlflogotextdecoration",$_POST["mnlflogotextdecoration"]);
			setConf("mnlflogotextalign",$_POST["mnlflogotextalign"]);
			setConf("mnlflogofontcolor",$_POST["mnlflogofontcolor"]);

			setConf("mnlftitlefontfamily",$_POST["mnlftitlefontfamily"]);
			setConf("mnlftitlefontsize",$_POST["mnlftitlefontsize"]);
			setConf("mnlftitlefontweight",$_POST["mnlftitlefontweight"]);
			setConf("mnlftitlefontstyle",$_POST["mnlftitlefontstyle"]);
			setConf("mnlftitletextdecoration",$_POST["mnlftitletextdecoration"]);
			setConf("mnlftitletextalign",$_POST["mnlftitletextalign"]);
			setConf("mnlftitlefontcolor",$_POST["mnlftitlefontcolor"]);
			
			setConf("mnlflinksfontfamily",$_POST["mnlflinksfontfamily"]);
			setConf("mnlflinksfontsize",$_POST["mnlflinksfontsize"]);
			setConf("mnlflinksfontweight",$_POST["mnlflinksfontweight"]);
			setConf("mnlflinksfontstyle",$_POST["mnlflinksfontstyle"]);
			setConf("mnlflinkstextdecoration",$_POST["mnlflinkstextdecoration"]);
			setConf("mnlflinkstextalign",$_POST["mnlflinkstextalign"]);
			setConf("mnlflinksfontcolor",$_POST["mnlflinksfontcolor"]);

			setConf("mnlfcontactfontfamily",$_POST["mnlfcontactfontfamily"]);
			setConf("mnlfcontactfontsize",$_POST["mnlfcontactfontsize"]);
			setConf("mnlfcontactfontweight",$_POST["mnlfcontactfontweight"]);
			setConf("mnlfcontactfontstyle",$_POST["mnlfcontactfontstyle"]);
			setConf("mnlfcontacttextdecoration",$_POST["mnlfcontacttextdecoration"]);
			setConf("mnlfcontacttextalign",$_POST["mnlfcontacttextalign"]);
			setConf("mnlfcontactfontcolor",$_POST["mnlfcontactfontcolor"]);
			
			setConf("mnlfcopyrightfontfamily",$_POST["mnlfcopyrightfontfamily"]);
			setConf("mnlfcopyrightfontsize",$_POST["mnlfcopyrightfontsize"]);
			setConf("mnlfcopyrightfontweight",$_POST["mnlfcopyrightfontweight"]);
			setConf("mnlfcopyrightfontstyle",$_POST["mnlfcopyrightfontstyle"]);
			setConf("mnlfcopyrighttextdecoration",$_POST["mnlfcopyrighttextdecoration"]);
			setConf("mnlfcopyrighttextalign",$_POST["mnlfcopyrighttextalign"]);
			setConf("mnlfcopyrightfontcolor",$_POST["mnlfcopyrightfontcolor"]);

			setConf("mnlfphototitlefontfamily",$_POST["mnlfphototitlefontfamily"]);
			setConf("mnlfphototitlefontsize",$_POST["mnlfphototitlefontsize"]);
			setConf("mnlfphototitlefontweight",$_POST["mnlfphototitlefontweight"]);
			setConf("mnlfphototitlefontstyle",$_POST["mnlfphototitlefontstyle"]);
			setConf("mnlfphototitletextdecoration",$_POST["mnlfphototitletextdecoration"]);
			setConf("mnlfphototitletextalign",$_POST["mnlfphototitletextalign"]);
			setConf("mnlfphototitlefontcolor",$_POST["mnlfphototitlefontcolor"]);
		
			setConf("mnlfphotodescriptionfontfamily",$_POST["mnlfphotodescriptionfontfamily"]);
			setConf("mnlfphotodescriptionfontsize",$_POST["mnlfphotodescriptionfontsize"]);
			setConf("mnlfphotodescriptionfontweight",$_POST["mnlfphotodescriptionfontweight"]);
			setConf("mnlfphotodescriptionfontstyle",$_POST["mnlfphotodescriptionfontstyle"]);
			setConf("mnlfphotodescriptiontextdecoration",$_POST["mnlfphotodescriptiontextdecoration"]);
			setConf("mnlfphotodescriptiontextalign",$_POST["mnlfphotodescriptiontextalign"]);
			setConf("mnlfphotodescriptionfontcolor",$_POST["mnlfphotodescriptionfontcolor"]);

			setConf("mnlfphotonavigationcontrolsfontfamily",$_POST["mnlfphotonavigationcontrolsfontfamily"]);
			setConf("mnlfphotonavigationcontrolsfontsize",$_POST["mnlfphotonavigationcontrolsfontsize"]);
			setConf("mnlfphotonavigationcontrolsfontweight",$_POST["mnlfphotonavigationcontrolsfontweight"]);
			setConf("mnlfphotonavigationcontrolsfontstyle",$_POST["mnlfphotonavigationcontrolsfontstyle"]);
			setConf("mnlfphotonavigationcontrolstextdecoration",$_POST["mnlfphotonavigationcontrolstextdecoration"]);
			setConf("mnlfphotonavigationcontrolsfontcolor",$_POST["mnlfphotonavigationcontrolsfontcolor"]);

			setConf("mnlfthumbnailsnavigationcontrolsfontfamily",$_POST["mnlfthumbnailsnavigationcontrolsfontfamily"]);
			setConf("mnlfthumbnailsnavigationcontrolsfontsize",$_POST["mnlfthumbnailsnavigationcontrolsfontsize"]);
			setConf("mnlfthumbnailsnavigationcontrolsfontweight",$_POST["mnlfthumbnailsnavigationcontrolsfontweight"]);
			setConf("mnlfthumbnailsnavigationcontrolsfontstyle",$_POST["mnlfthumbnailsnavigationcontrolsfontstyle"]);
			setConf("mnlfthumbnailsnavigationcontrolstextdecoration",$_POST["mnlfthumbnailsnavigationcontrolstextdecoration"]);
			setConf("mnlfthumbnailsnavigationcontrolsfontcolor",$_POST["mnlfthumbnailsnavigationcontrolsfontcolor"]);
			
			setConf("mnlftdthumbborderwidth",$_POST["mnlftdthumbborderwidth"]);
			setConf("mnlftdthumbborderstyle",$_POST["mnlftdthumbborderstyle"]);
			setConf("mnlftdthumbbordercolor",$_POST["mnlftdthumbbordercolor"]);

			setConf("mnlftdthumbselectedborderwidth",$_POST["mnlftdthumbselectedborderwidth"]);
			setConf("mnlftdthumbselectedborderstyle",$_POST["mnlftdthumbselectedborderstyle"]);
			setConf("mnlftdthumbselectedbordercolor",$_POST["mnlftdthumbselectedbordercolor"]);

			setConf("mnlfimgthumbborderwidth",$_POST["mnlfimgthumbborderwidth"]);
			setConf("mnlfimgthumbborderstyle",$_POST["mnlfimgthumbborderstyle"]);
			setConf("mnlfimgthumbbordercolor",$_POST["mnlfimgthumbbordercolor"]);
			setConf("mnlfimgthumbopacity",$_POST["mnlfimgthumbopacity"]);

			setConf("mnlfimgthumbselectedborderwidth",$_POST["mnlfimgthumbselectedborderwidth"]);
			setConf("mnlfimgthumbselectedborderstyle",$_POST["mnlfimgthumbselectedborderstyle"]);
			setConf("mnlfimgthumbselectedbordercolor",$_POST["mnlfimgthumbselectedbordercolor"]);
			setConf("mnlfimgthumbselectedopacity",$_POST["mnlfimgthumbselectedopacity"]);

			setConf("mnlfimgthumbhoverborderwidth",$_POST["mnlfimgthumbhoverborderwidth"]);
			setConf("mnlfimgthumbhoverborderstyle",$_POST["mnlfimgthumbhoverborderstyle"]);
			setConf("mnlfimgthumbhoverbordercolor",$_POST["mnlfimgthumbhoverbordercolor"]);
			setConf("mnlfimgthumbhoveropacity",$_POST["mnlfimgthumbhoveropacity"]);

			setConf("mnlfimgphotoborderwidth",$_POST["mnlfimgphotoborderwidth"]);
			setConf("mnlfimgphotoborderstyle",$_POST["mnlfimgphotoborderstyle"]);
			setConf("mnlfimgphotobordercolor",$_POST["mnlfimgphotobordercolor"]);

			setConf("mnlfdivphotoborderwidth",$_POST["mnlfdivphotoborderwidth"]);
			setConf("mnlfdivphotoborderstyle",$_POST["mnlfdivphotoborderstyle"]);
			setConf("mnlfdivphotobordercolor",$_POST["mnlfdivphotobordercolor"]);

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

	<form id="mnlform" <? if((isset($view) && ($view == "layouts" || $view == "branding")) || getTypedConf("Username") == NULL) echo "enctype=\"multipart/form-data\""; ?>  <? if(!isset($view)) echo "action=\"mnlfAdmin.php\""; else echo "action=\"mnlfAdmin.php?view=".$view."\""; ?>  method="post">

<?

// If asked, display password renew page
if(isset($_POST["pwdRenew"]) && $_POST["pwdRenew"] == "true") {
	unset($_SESSION["username"]);
	unset($_SESSION["password"]);
?>
	<table border="0" class="normal" align="center">
		<tr>
			<td><? echo getResource("pwdQuestion"); ?> :</td>
			<td><? echo getTypedConf("PwdQuestion"); ?> ?</td>
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


if(getTypedConf("Username") == NULL) {
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

		if(getTypedConf("Username") != NULL) {
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

if(!isset($_SESSION["username"]) && getTypedConf("Username") == NULL) {
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
	
		// If asked, add an existing flickr account
		if(isset($_POST["addAnotherAccount"]) && $_POST["addAnotherAccount"] == "true") {
			addTempConf("IsAddingExistingFlickrAccount","true","bool");
		} else if(isset($_POST["addAnotherAccountCancel"]) && $_POST["addAnotherAccountCancel"] == "true") {
			deleteTempConf("IsAddingExistingFlickrAccount");
			
?>

			<SCRIPT language="JavaScript">
				window.location="mnlfAdmin.php?view=sets";
			</SCRIPT>
				
<?				
				
		}
	
?>

		<input type="hidden" name="signout" value="false" />

		<ul id="admintab">
				     
			<li <? if(!isset($view) && !isset($_REQUEST["frob"])) echo "class=\"active\""; ?>><a href="mnlfAdmin.php"><? echo getResource("tabLabelConfiguration"); ?></a></li>
<?
		if(getTypedConf("AuthTokens") != NULL && !getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {
?>
		     <li <? if(isset($view) && $view == "css") echo "class=\"active\""; ?>><a href="mnlfAdmin.php?view=css"><? echo getResource("tabLabelAppearance"); ?></a></li>
	     	 <li <? if(isset($view) && $view == "layouts") echo "class=\"active\""; ?>><a href="mnlfAdmin.php?view=layouts"><? echo getResource("tabLayouts"); ?></a></li>
	     	 <li <? if(isset($view) && $view == "branding") echo "class=\"active\""; ?>><a href="mnlfAdmin.php?view=branding"><? echo getResource("tabBranding"); ?></a></li>
		     <li <? if(isset($view) && $view == "sets") echo "class=\"active\""; ?>><a href="mnlfAdmin.php?view=sets"><? echo getResource("tabLabelMySets"); ?></a></li>
			 <li class="signout"><input type="button" onClick="javascript:this.form.signout.value=true;this.form.submit();" <? echo "value=\"".getResource("btnSignOut")."\""; ?> /></li>
			 <li <? if(isset($view) && $view == "more") echo "class=\"active\"";  ?>><a href="mnlfAdmin.php?view=more"><? echo getResource("tabLabelMore"); ?></a></li>

<?
		}

		if(getTypedConf("AuthTokens") != NULL && !getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {
?>
		     <li class="seefolio"><a href="index.php"><? echo getResource("tabLabelSeeFolio"); ?></a></li>
<?
		}
?>


		</ul>
<?
if(!((getTypedConf("ApiKey") == NULL || getTypedConf("ApiSecret") == NULL || getTypedConf("AuthTokens") == NULL || isset($_REQUEST["frob"])))) {
	
	
?>		
		<center>
<?
}
?>
		<table border="0" class="normal">
	<?
	
		if((getTypedConf("ApiKey") == NULL || getTypedConf("ApiSecret") == NULL || getTypedConf("AuthTokens") == NULL || isset($_REQUEST["frob"]))
			|| getTypedConf("_TemporaryIsAddingExistingFlickrAccount"))		
			include("install.php");


		if(!isset($view) && getTypedConf("ApiKey") != NULL && getTypedConf("ApiSecret") != NULL && getTypedConf("ApiSecret") != NULL && getTypedConf("AuthTokens") != NULL && !isset($_REQUEST["frob"])  && !getTypedConf("_TemporaryIsAddingExistingFlickrAccount"))
			getFormConf();

		elseif(isset($view) && $view == "layouts" && (getTypedConf("ApiKey") != NULL && getTypedConf("ApiSecret") != NULL && getTypedConf("AuthTokens") != NULL)  && !getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {
?>
		<br /><br />
		<table cellpadding="15" class="styled">
			<tr>
				<td valign="top" align="center">
<?
			getUploader("design/layouts/navigation", "", "NavigationLayout", getResource("messageUploadNavigationLayout"), getResource("btnUpload"), getResource("messageDeleteNavigationLayout"));
?>
				</td>
				<td valign="top" align="center">
<?
			getUploader("design/layouts/viewer", "", "ViewerLayout", getResource("messageUploadViewerLayout"), getResource("btnUpload"), getResource("messageDeleteViewerLayout"));
?>
			 	</td>
			</tr>
		</table>
<?
		}

		elseif(isset($view) && $view == "branding" && (getTypedConf("ApiKey") != NULL && getTypedConf("ApiSecret") != NULL && getTypedConf("AuthTokens") != NULL)  && !getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {
?>
		<br /><br />
		<table cellpadding="15" class="styled">
			<tr>
				<td valign="top" align="center">
<?
			getUploader("design/images/logos", "", "Logo", getResource("messageUploadLogo"), getResource("btnUpload"), getResource("messageDeleteLogo"));
?>
				</td>
			</tr>
		</table>
		<br /><br />
		<table cellpadding="15" class="styled">
			<tr>
				<td valign="top" align="center">
<?
			getUploader("design/fonts", "", "Font", getResource("messageUploadFont"), getResource("btnUpload"), getResource("messageDeleteFontFolder"), getResource("messageUploadFontInstructions"), true, true);
?>
				</td>
			</tr>
		</table>
<?
		}
			
		elseif(isset($view) && $view == "sets" && (getTypedConf("ApiKey") != NULL && getTypedConf("ApiSecret") != NULL && getTypedConf("AuthTokens") != NULL)  && !getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {

?>			
				<br /><br />
<?
				getSetsSelector();
		}

		elseif(isset($view) && $view == "more"  && !getTypedConf("_TemporaryIsAddingExistingFlickrAccount"))
			getMoreForm();

		
		elseif(isset($view) && $view == "css" && (getTypedConf("ApiKey") != NULL && getTypedConf("ApiSecret") != NULL && getTypedConf("AuthTokens") != NULL)  && !getTypedConf("_TemporaryIsAddingExistingFlickrAccount")) {
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
			<p class="button"><input type="submit" <? echo "value=\"".getResource("btnConnect")."\""; ?>></p>
		</center>
<?
		}

		if(isset($_POST["pwdRenew"]) && $_POST["pwdRenew"] == "true") {
?>
		<center>
			<p class="button"><input type="submit" <? echo "value=\"".getResource("btnRenewPwd")."\""; ?>></p>
		</center>
<?
		}

if(getTypedConf("Username") == NULL) {
?>
	<input type="hidden" name="importConfigFiles" value="false" />
	<table class="normal" align="center" border="0">
		<tr>
			<td colspan="2"><br />
				<p class="warning"><? echo getResource("messageImportConfiguration"); ?></p>
			</td>
		</tr>
		<tr>
			<td><? echo getResource("configFile"); ?> :</td>
			<td><input name="configFile" type="file" /></td>
		</tr>
		<tr>
			<td><? echo getResource("appearanceFile"); ?> :</td>
			<td><input name="appearanceFile" type="file" /></td>
		</tr>
		<tr>
			<td><? echo getResource("headFile"); ?> :</td>
			<td><input name="headFile" type="file" /></td>
		</tr>
	</table>
	<center>
		<p class="button"><input type="button" <? echo "value=\"".getResource("btnImport")."\""; ?> onClick="javascript:this.form.importConfigFiles.value=true;this.form.submit();"></p>
	</center>
<?
}
?>

		</form>
	</center>	

	<?
			echo "<p align=\"center\">v.".getTypedConf("Version")."</p>";
	?>

</body>
</html>