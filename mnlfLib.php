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

require_once("phpFlickr.php");
require_once("config/mnlfConfig.php");

define("STANDARD_FONT_FAMILIES",  "Arial|Arial Black|Comic Sans MS|Courier New|Georgia|Impact|Symbol|Times New Roman|Trebuchet MS|Verdana");
$fontFamilies = array_merge(array(), explode("|", STANDARD_FONT_FAMILIES));
$fontDirectory = "design/fonts";
if (file_exists($fontDirectory)) {
    $mydir = opendir($fontDirectory);
    while(false !== ($file = readdir($mydir))) {
    	if($file != "." && $file != ".." && is_dir($fontDirectory."/".$file)) {
    		array_push($fontFamilies, $file);
    	}
    }
	closedir($mydir);
}

define("FONT_FAMILIES",  implode("|", $fontFamilies));

// =============================================
// = Designs the appropriate navigation layout =
// =============================================
function getNavigationLayout($setid = NULL) {

	$layout = "";

	if(getTypedConf("AuthTokens") != NULL && getTypedConf("ShowNavigationMenu") == "true") {

		$selectedSets = explode(",",getTypedConf("SelectedSets"));
		$delimiter = getTypedConf("DelimiterSetsTitles");
		$activeSet = "";
		$setsLinksAlignment = getListSelectedValue("SetsLinksAlignment");

		for($i = 0; $i < count($selectedSets); $i++) {
			if($setid == $selectedSets[$i]) {
				$activeSet = $selectedSets[$i];
				break;
			}
		}

		$nSetByLine = getTypedConf("SetByLine");

		include("design/layouts/navigation/".getFileListSelectedValue("NavigationLayout"));

	}

	return $layout;

}

// =========================================
// = Designs the appropriate viewer layout =
// =========================================
function getViewerLayout($setId, $setPage, $photoid, $showPreviousPhoto, $showNextPhoto, $showPreviousThumbnailsPage, $showNextThumbnailsPage) {

	$layout = "";

	if(strpos(getTypedConf("SelectedSets"), $setId) !== false) {

	  	$objectsInstances = getFlickrObjectsInstances(true);
 		$accountSet = explode(".",$setId);

		if(count($accountSet) == 2) {

		  $account = $accountSet[0];
		  $set = $accountSet[1];
		  $f = $objectsInstances[$account];

	  	$photoInfo = $f->photos_getInfo($photoid,NULL);
		  $photosize = $f->photos_getSizes($photoid, $secret = NULL);
		  $photoFormat = getListSelectedValue("PhotoSize");
		  $photos_url = getPhotosUrl($f,$set);
		  $photoPage = $photos_url.$photoid;
		  $photoStaticUrlForDownload = $f->buildPhotoURL($photoInfo["photo"], getListSelectedValue("DownloadPhotoSize"));

		  $size = NULL;
		  for ($i = 0; $i < $photosize; $i++) {
		    if(strtolower($photosize[$i]['label']) == strtolower($photoFormat)) {
		    	$size = $photosize[$i];
		    	break;
		    }
		  }

		  $photoDescription = "";
		  $photoTitle = "";
		  if(getTypedConf("ShowPhotoDescription") == "true") {
			$photoDescription = $photoInfo['photo']['description'];
		  }
		  if(getTypedConf("ShowPhotoTitle") == "true")
			$photoTitle = $photoInfo['photo']['title'];

			require("config/css.php");
			$borderDiv = str_replace("px","",$mnlfdivphotoborderwidth);
			$borderImg = str_replace("px","",$mnlfimgphotoborderwidth);
		    $tweakSaveImageAs = getTypedConf("TweakSaveImageAs");
		    $maxWidthImg = getTypedConf("ImgMaxWidth");
		    $ratio = 1;
			if($maxWidthImg > -1) {
				if($size['width'] > $maxWidthImg) {
					$ratio = $maxWidthImg/$size['width'];
				}
			}
			$widthImg = ($size['width']*$ratio)+($borderImg+$borderDiv);
			$heightImg = ($size['height']*$ratio)+($borderImg+$borderDiv);

			$nColumns = getTypedConf("Columns");
			$nRows = getTypedConf("Rows");

			$previousPhotoControlLabel = getTypedConf("PreviousPhotoControlLabel");
			$nextPhotoControlLabel = getTypedConf("NextPhotoControlLabel");
			$previousThumbnailsPageControlLabel = getTypedConf("PreviousThumbnailsPageControlLabel");
			$nextThumbnailsPageControlLabel = getTypedConf("NextThumbnailsPageControlLabel");

			 $photosThumbs = $f->photosets_getPhotos($set,NULL, "public", ($nColumns*$nRows), $setPage, NULL);
			 $i = 0;

			$nGUIWidth = getTypedConf("GUIWidth");
			$nGUIHeight = getTypedConf("GUIHeight");

			$displayDownloadLink = getTypedConf("DisplayDownloadLink");
			$downloadLinkLabel = getTypedConf("DownloadLinkLabel");
			$downloadPhotoSize = getListSelectedValue("DownloadPhotoSize");
			$photoLinkToFlickrLabel = getTypedConf("PhotoLinkToFlickrLabel");
			$photoLinkToFlickrAlignment = getListSelectedValue("PhotoLinkToFlickrAlignment");

			include("design/layouts/viewer/".getFileListSelectedValue("ViewerLayout"));

		}

	}

	return $layout;

}


// ===============================================================
// = Store the token returned by Flickr API into the config File =
// ===============================================================
function setFrob($frob) {

	$objectsInstances = getFlickrObjectsInstances();
	$f = $objectsInstances[0];
	$token = $f->auth_getToken ($frob);
	$newTokens = getTypedConf("AuthTokens");
	if(substr_count($newTokens,$token["token"]) <= 0) {
		if(strlen($newTokens) != 0)
			$newTokens .= "|".$token["token"];
		else
			$newTokens .= $token["token"];
		setTypedConf("AuthTokens",$newTokens);
	}

}


function getFlickrObjectsInstances($enableCache = false) {

	$apiKey = getTypedConf("ApiKey");
	$apiSecret = getTypedConf("ApiSecret");
	$authTokens = getTypedConf("AuthTokens");
	$cacheDir = getTypedConf("CacheDir");
	$cacheTimeToLive = getTypedConf("CacheTimeToLive");

	$objectsInstances = null;

	if($apiKey != NULL && $apiSecret != NULL) {

		$authToken = explode("|",$authTokens);

		if(count($authToken) == 0) {

			$f = new phpFlickr($apiKey,$apiSecret);
			if($enableCache == true && $cacheDir != NULL && $cacheTimeToLive != NULL)
				$f->enableCache("fs", $cacheDir, $cacheTimeToLive);
			$objectsInstances[] = $f;

		} else {

			for($i = 0; $i < count($authToken); $i++) {

				$f = new phpFlickr($apiKey,$apiSecret);
				if($authToken[$i] != NULL)
					$f->setToken($authToken[$i]);
				if($enableCache == true && $cacheDir != NULL && $cacheTimeToLive != NULL)
					$f->enableCache("fs", $cacheDir, $cacheTimeToLive);

				$objectsInstances[] = $f;
			}

		}
		return $objectsInstances;

	}
	else
		return NULL;

}

// =============================
// = Gets Flickr account Owner =
// =============================
function getOwner($f) {
	$photosets = $f->photosets_getList(NULL, NULL, NULL, NULL);
	$owner = "";
	foreach ($photosets['photoset'] as $set) {
		$setInfo = $f->photosets_getInfo($set['id']);
		$owner = $f->people_getInfo($setInfo['owner']);
		$owner = $owner['realname'];
		break;
	}
	return $owner;
}

// ==============================
// = Gets sets owner photos url =
// ==============================
function getPhotosUrl($f,$set) {
	$setInfo = $f->photosets_getInfo($set);
	$owner = $f->people_getInfo($setInfo['owner']);
	if(isset($owner['photosurl']))
		return $owner['photosurl'];
	else
		return "";
}

// ==================
// = Cleans session =
// ==================
function cleanSession() {
	session_destroy();
}


// ======================================================
// = Gets a configuration list attribute selected value =
// ======================================================
function getListSelectedValue($attribute,$configFile = NULL){

	if($configFile == NULL)
		$configFile = "config/mnlfConfig.php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$val = trim($varVal[1]);
			if($var == "\$list".$attribute) {
				$val = substr($val,1,-2);
				$val = explode("|",trim($val));
				return $val[0];
			}
		}
	}

	return NULL;

}


// ==============================================
// = Gets a configuration list attribute values =
// ==============================================
function getListValues($attribute,$configFile = NULL){

	if($configFile == NULL)
		$configFile = "config/mnlfConfig.php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$val = trim($varVal[1]);
			if($var == "\$list".$attribute) {
				$val = substr($val,1,-2);
				$val = explode("|",trim($val));
				return explode(",",$val[1]);
			}
		}
	}

	return NULL;

}

// =============================================================
// = Gets a configuration "file list" attribute selected value =
// =============================================================
function getFileListSelectedValue($attribute,$configFile = NULL){

	if($configFile == NULL)
		$configFile = "config/mnlfConfig.php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$val = trim($varVal[1]);
			if($var == "\$filelist".$attribute) {
				$val = substr($val,1,-2);
				$val = explode("|",trim($val));
				return $val[0];
			}
		}
	}

	return NULL;

}

// =====================================================
// = Gets a configuration "file list" attribute values =
// =====================================================
function getFileListValues($attribute,$configFile = NULL){

	if($configFile == NULL)
		$configFile = "config/mnlfConfig.php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$val = trim($varVal[1]);
			if($var == "\$filelist".$attribute) {
				$val = substr($val,1,-2);
				$val = explode("|",trim($val));
				$results = array();
				if (file_exists($val[1])) {
				    $mydir = opendir($val[1]);
				    while(false !== ($file = readdir($mydir))) {
				        if($file != "." && $file != ".." && !is_dir($file)) {
				            $results[] = $file;
				        }
				    }
					closedir($mydir);
				}
			    return $results;
			}
		}
	}

	return NULL;

}

// ===========================================
// = Gets resources configuration attributes =
// ===========================================
function getResources(){

	if(!isset($_SESSION["lang"]) && getTypedConf("lang") == NULL)
		$_SESSION["lang"] = getListSelectedValue("Language");

	$configFile = "design/resources/resources-".$_SESSION["lang"].".php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			//error_log($varVal);
			unset($varVal[0]);
			//error_log($varVal);
			$var = substr($var,1,strlen($var)-1);
			$val = implode("=",$varVal);
			$val = substr($val,0,-1);
			$rsrc[$_SESSION["lang"]."|".$var] = substr($val,1,-1);
		}
	}

	$_SESSION["rsrc"] = $rsrc;

}

// ===========================================
// = Gets resources configuration attributes =
// ===========================================
function getResource($name){

	if(!isset($_SESSION["rsrc"]))
		getResources();

	return $_SESSION["rsrc"][$_SESSION["lang"]."|".$name];

}

// ========================================
// = Gets a configuration attribute =
// ========================================
function getConf($attribute,$configFile = NULL){

	if($configFile == NULL)
		$configFile = "config/css.php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$val = trim($varVal[1]);
			if($var == "\$".$attribute)
				return substr($val,1,-2);
		}
	}

	return NULL;

}



// ========================================
// = Gets a configuration typed attribute =
// ========================================
function getTypedConf($attribute,$configFile = NULL){

	if($configFile == NULL)
		$configFile = "config/mnlfConfig.php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$val = trim($varVal[1]);
			if($var == "\$str".$attribute || $var == "\$list".$attribute || $var == "\$filelist".$attribute)
				return substr($val,1,-2);
			if($var == "\$n".$attribute || $var == "\$bool".$attribute)
				return substr($val,0,-1);
		}
	}

	return NULL;

}

// ========================================
// = Sets a configuration typed attribute =
// ========================================
function setTypedConf($attribute,$value,$encrypt=false,$configFile = NULL){

	if($configFile == NULL)
		$configFile = "config/mnlfConfig.php";

	$previousConfFile = file ($configFile);
	$confFileBak = fopen($configFile.".bak", "w");
	fwrite($confFileBak, file_get_contents($configFile));
	fclose($confFileBak);

	$newContent = "";
	while (list ($lineNb, $line) = each ($previousConfFile)) {

		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$val = trim($varVal[1]);

			if($encrypt == true)
				$val = md5($value);

			if($var == "\$str".$attribute)
				$line = $var." = \"".$value."\"; \n";

			elseif($var == "\$n".$attribute)
				$line = $var." = ".$value."; \n";

			elseif($var == "\$bool".$attribute)
				$line = $var." = ".$value."; \n";

			elseif($var == "\$list".$attribute) {

				$val = substr($val, 1, -1);
				$listElements = explode("|",trim($val));
				$line = $var." = \"".$value."|".$listElements[1]."; \n";
			}

			elseif($var == "\$filelist".$attribute) {
				$val = substr($val, 1, -1);
				$filelistElements = explode("|",trim($val));
				$line = $var." = \"".$value."|".$filelistElements[1]."; \n";
			}

		}

		$newContent .= $line;

	}

	$newConfFile = fopen($configFile, "w");
	fwrite($newConfFile, $newContent);
	fclose($newConfFile);

	if(getTypedConf($attribute) == $value)
		return true;
	else
		return false;

}

// ============================================
// = Adds a temporary configuration attribute =
// ============================================
function addTempConf($attribute,$value,$type,$encrypt=false){

	$configFile = "config/mnlfConfig.php";

	$previousConfFile = file ($configFile);
	$confFileBak = fopen($configFile.".bak", "w");
	fwrite($confFileBak, file_get_contents($configFile));
	fclose($confFileBak);

	$newContent = "";
	while (list ($lineNb, $line) = each ($previousConfFile))
		if(substr_count($line,"?>") <= 0)
			$newContent .= $line;

	if($encrypt == true)
		$value = md5($value);

	if($type == "str")
		$newContent .= "\$str_Temporary$attribute= \"$value\";\n?>";
	elseif($type == "n")
		$newContent .= "\$n_Temporary$attribute= $value;\n?>";
	elseif($type == "bool")
		$newContent .= "\$bool_Temporary$attribute= $value;\n?>";
	elseif($type == "list")
		$newContent .= "\$list_Temporary$attribute= \"$value\";\n?>";
	elseif($type == "filelist")
		$newContent .= "\$list_Temporary$attribute= \"$value\";\n?>";

	$newConfFile = fopen($configFile, "w");
	fwrite($newConfFile, $newContent);
	fclose($newConfFile);

}

// ============================================
// = Clean a temporary configuration attribute =
// ============================================
function deleteTempConf($attribute){

	$configFile = "config/mnlfConfig.php";

	$previousConfFile = file ($configFile);
	$confFileBak = fopen($configFile.".bak", "w");
	fwrite($confFileBak, file_get_contents($configFile));
	fclose($confFileBak);

	$newContent = "";
	while (list ($lineNb, $line) = each ($previousConfFile))
		if(substr_count($line,"_Temporary$attribute") <= 0)
			$newContent .= $line;

	$newConfFile = fopen($configFile, "w");
	fwrite($newConfFile, $newContent);
	fclose($newConfFile);

}


// ======================================
// = Sets a configuration attribute =
// ======================================
function setConf($attribute,$value, $configFile = NULL){

	if($configFile == NULL)
		$configFile = "config/css.php";

	$previousConfFile = file ($configFile);
	$confFileBak = fopen($configFile.".bak", "w");
	fwrite($confFileBak, file_get_contents($configFile));
	fclose($confFileBak);

	$newContent = "";
	while (list ($lineNb, $line) = each ($previousConfFile)) {

		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);

			if($var == "$".$attribute)
				$line = $var." = \"".$value."\"; \n";

		}

		$newContent .= $line;

	}

	$newConfFile = fopen($configFile, "w");
	fwrite($newConfFile, $newContent);
	fclose($newConfFile);

	if(getTypedConf($attribute) == $value)
		return true;
	else
		return false;

}


// ========================================================
// = Get conf attributes and display a modification entry =
// ========================================================
function getFormConf(){

	$configFile = "config/mnlfConfig.php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$val = trim($varVal[1]);
			$isBool = false;
			$isList = false;
			$isFileList = false;

			if(substr($var, 0, 4) == "\$str") {
				$val = substr($val,1,-2);
			} elseif(substr($var, 0, 5) == "\$bool") {
				$val = substr($val,0,-1);
				$isBool = true;
			} elseif(substr($var, 0, 2) == "\$n") {
					$val = substr($val,0,-1);
			} elseif(substr($var, 0, 5) == "\$list") {
					$isList = true;
			} elseif(substr($var, 0, 9) == "\$filelist") {
							$isFileList = true;
			} elseif(substr($var, 0, 5) != "\$bool" && substr($var, 0, 2) != "\$n") {
				continue;
			}


			$desc = "";
			if(isset($valDesc) && count($valDesc) > 1)
				$desc = $valDesc[1];

			if($var != "" && $var != "\$strUsername"
					&& $var != "\$strPassword"  && $var != "\$strPwdQuestion" && $var != "\$strPwdAnswer" && $var != "\$strApi_sig"
						&& $var != "\$strAuthUrl" && $var != "\$strAuthTokens"
							&& $var != "\$strPerms" && $var != "\$strFrob"
								&& $var != "\$strSelectedSets" && $var != "\$strDefaultSet" && $var != "\$strCacheDir"
								 	&& $var != "\$strApiKey" && $var != "\$strApiSecret" && $var != "\$strVersion" && $var != "\$strSelectedSetsTitles" && $var != "\$strSelectedSetsThumbnails" && substr_count($var,"_Temporary") <= 0) {

?>
				<tr>
					<td><? echo getResource("config".getDispVar($var))." : "; ?></td>
<?
					if($isBool) {
?>
					<td>
					 <select <? echo "name=\"".getDispVar($var)."\""; ?> >
						<option value="false" <? if($val == "false") echo "selected" ?>><? echo getResource("configFalse"); ?></option>
						<option value="true" <? if($val == "true") echo "selected" ?>><? echo getResource("configTrue"); ?></option>
					 </select>
					</td>
<?
					} elseif($isList) {

						$val = getListSelectedValue(getDispVar($var));
						$listElements = getListValues(getDispVar($var));
?>
					<td>
					 <select <? echo "name=\"".getDispVar($var)."\""; ?> >
<?
						foreach($listElements as $listElement) {
							if($listElement == $val)
								echo "<option value=\"$listElement\" selected>$listElement</option>";
							else
								echo "<option value=\"$listElement\">$listElement</option>";
						}
?>
					 </select>
					</td>
<?
					} elseif($isFileList) {

						$val = getFileListSelectedValue(getDispVar($var));
						$listElements = getFileListValues(getDispVar($var));
?>
					<td>
					 <select <? echo "name=\"".getDispVar($var)."\""; ?> >
<?
						foreach($listElements as $listElement) {
							if($listElement == $val)
								echo "<option value=\"$listElement\" selected>$listElement</option>";
							else
								echo "<option value=\"$listElement\">$listElement</option>";
						}
?>
					 </select>
					</td>
<?
					} else {
?>
					<td><input type="text" size="50" <? echo "name=\"".getDispVar($var)."\""; ?> <? echo "value=\"".stripslashes($val)."\""; ?> /></td>
<?
					}
?>
					<td><? if(isset($valDesc)) echo $desc; ?></td>
				</tr>
<?
			}

		} elseif(mb_eregi("^\\//",trim($line))) {
			if(trim($line) != "//mnlfolio admin account" && trim($line) != "//flickrParams") {
?>
			<tr>
				<td colspan="3" class="comment"><br /><? echo getResource(substr(trim($line),2,strlen(trim($line))-2)); ?></td>
			</tr>
<?
			}
		}

	}

?>
			<tr>
				<td colspan="3" align="right"><p class="importantButton"><input type="submit" <? echo "value=\">>>> ".getResource("btnSave")." <<<<\""; ?>></p></td>
			</tr>
<?
}

// =========================
// = Save conf attributes  =
// =========================
function saveConf(){

	$configFile = "config/mnlfConfig.php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(mb_eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			if($var != "" && $var != "\$strUsername" && $var != "\$strPassword" && $var != "\$strPwdQuestion" && $var != "\$strPwdAnswer" && $var != "\$strApi_sig" && $var != "\$strAuthUrl" && $var != "\$strAuthTokens" && $var != "\$strPerms" && $var != "\$strFrob" && $var != "\$strSelectedSets" && $var != "\$strDefaultSet" && $var != "\$strCacheDir" && $var != "\$strSelectedSetsTitles" && $var != "\$strSelectedSetsThumbnails"  && substr_count($var,"_Temporary") <= 0) {
				if(isset($_POST[getDispVar($var)]))
					setTypedConf(getDispVar($var),trim($_POST[getDispVar($var)]));
			}
		}
	}

}

// =======================================
// = Get the displayable config variable =
// =======================================
function getDispVar($var){

	if(substr($var, 0, 4) == "\$str")
		return substr($var, 4);

	elseif(substr($var, 0, 2) == "\$n")
		return substr($var, 2);

	elseif(substr($var, 0, 5) == "\$bool")
		return substr($var, 5);

	elseif(substr($var, 0, 5) == "\$list")
		return substr($var, 5);

	elseif(substr($var, 0, 9) == "\$filelist")
		return substr($var, 9);

	return NULL;

}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

// ========================
// = Displays an uploader =
// ========================
function getUploader($targetDirectory, $action, $object, $uploadFieldLabel, $uploadButtonLabel, $deleteLabel, $uploadInstructions = "", $isMultiple = false, $showDirectoriesOnly = false) {
	echo "<input type=\"hidden\" name=\"uploadFile$object\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"removeFile$object\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"targetDirectory$object\" value=\"$targetDirectory\" />";
	if($isMultiple) {
		echo "<p class=\"title\">$uploadFieldLabel :</p><input name=\"uploadedFile".$object."[]\" type=\"file\" multiple/><br/>";
	} else {
		echo "<p class=\"title\">$uploadFieldLabel :</p><input name=\"uploadedFile$object\" type=\"file\" /><br/>";
	}
	echo $uploadInstructions."<br/>";
	echo "<p class=\"button\"><input type=\"button\" value=\"$uploadButtonLabel\" onClick=\"javascript:this.form.uploadFile$object.value=true;this.form.submit();\" /></p>";

	echo "<p class=\"title\">$deleteLabel :</p>";
	echo "<select name=\"selectedFiles".$object."[]\" multiple >";

	if (file_exists($targetDirectory)) {
	    $mydir = opendir($targetDirectory);
	    while(false !== ($file = readdir($mydir))) {
	        if($file != "." && $file != "..") {
	        	if($showDirectoriesOnly && is_dir($targetDirectory."/".$file)) {
					echo "<option value=\"$file\">$file</option>";
	        	} elseif (!$showDirectoriesOnly) {
	        		echo "<option value=\"$file\">$file</option>";
	        	}
	        }
	    }
		closedir($mydir);
	}
	echo "</select>";
	echo "<p class=\"button\"><input type=\"button\" value=\"".getResource("btnDelete")."\" onClick=\"javascript:this.form.removeFile$object.value=true;this.form.submit();\" /></p>";
}

// ==========================
// = Displays the More Form =
// ==========================
function getMoreForm() {

	if(isset($_POST["saveHead"]) && $_POST["saveHead"] == "true" && isset($_POST["content"]))
		savefile("config/head.html",$_POST["content"]);

	echo "<input type=\"hidden\" name=\"resetAll\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"resetFlickr\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"editHead\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"saveHead\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"exportHead\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"exportConfig\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"exportAppearance\" value=\"false\" />";

	echo "<br/><br>";
	echo "<table class=\"normal styled\" width=\"600\" cellpadding=\"8\">";
	echo "<tr><td>";
		echo getResource("configResetAll");
	echo "</td>";
	echo "<td align=\"center\" valign=\"middle\"><p class=\"button\"><input type=\"button\" value=\"".getResource("btnResetAll")."\" onClick=\"javascript:this.form.resetAll.value=true;this.form.submit();\" /></p></td></tr>";
	echo "<tr><td>";
	echo getResource("configResetFlickrLink");
	echo "</td><td align=\"center\" valign=\"middle\"><p class=\"button\"><input type=\"button\" value=\"".getResource("btnResetFlickrLink")."\" onClick=\"javascript:this.form.resetFlickr.value=true;this.form.submit();\" /></p></td></tr>";
	echo "<tr><td>";
	echo getResource("configEditHead");
	echo "</td><td align=\"center\" valign=\"middle\"><p class=\"button\"><input type=\"button\" value=\"".getResource("btnEditHead")."\" onClick=\"javascript:this.form.editHead.value=true;this.form.exportAppearance.value=false;this.form.exportConfig.value=false;this.form.submit();\" /></p></td></tr>";
	if(isset($_POST["editHead"]) && $_POST["editHead"] == "true") {
	echo "<tr><td colspan=\"2\" align=\"center\">";
	echo "<textarea rows=\"25\" cols=\"80\" name=\"content\">";

	if(!file_exists("config/head.html"))
		copy ("default/head.html", "config/head.html");
	echo file_get_contents("config/head.html", true);

	echo "</textarea><p class=\"button\"><input type=\"button\" value=\"".getResource("btnSave")."\" onClick=\"javascript:this.form.saveHead.value=true;this.form.editHead.value=false;this.form.submit();\" /><input type=\"button\" value=\"".getResource("btnCancel")."\" onClick=\"javascript:this.form.editHead.value=false;this.form.submit();\" /></p></td></tr>";
	}
	echo "<tr><td>";
	echo getResource("configExportHead");
	echo "</td><td align=\"center\" valign=\"middle\"><p class=\"button\"><input type=\"button\" value=\"".getResource("btnExportHead")."\" onClick=\"javascript:this.form.exportHead.value=true;this.form.exportConfig.value=false;this.form.exportAppearance.value=false;this.form.editHead.value=false;this.form.submit();\" /></p></td></tr>";
	echo "<tr><td>";
	echo getResource("configExportConfigFile");
	echo "</td><td align=\"center\" valign=\"middle\"><p class=\"button\"><input type=\"button\" value=\"".getResource("btnExportConfiguration")."\" onClick=\"javascript:this.form.exportConfig.value=true;this.form.exportAppearance.value=false;this.form.editHead.value=false;this.form.exportHead.value=false;this.form.submit();\" /></p></td></tr>";
	echo "<tr><td>";
	echo getResource("configExportAppearanceFile");
	echo "</td><td align=\"center\" valign=\"middle\"><p class=\"button\"><input type=\"button\" value=\"".getResource("btnExportAppearance")."\" onClick=\"javascript:this.form.exportAppearance.value=true;this.form.exportConfig.value=false;this.form.editHead.value=false;this.form.exportHead.value=false;this.form.submit();\" /></p></td></tr>";


	$objectsInstances = getFlickrObjectsInstances();

	echo "<tr><td>".getResource("configApiKey")."</td>";
	echo "<td align=\"center\" valign=\"middle\">".getTypedConf("ApiKey")."</td></tr>";

	echo "<tr><td>".getResource("configApiSecret")."</td>";
	echo "<td align=\"center\" valign=\"middle\">".getTypedConf("ApiSecret")."</td></tr>";


	echo "</table>";

}

// =======================================
// = Displays all the sets as a dropdown =
// =======================================
function getSetsSelector() {

	if(getTypedConf("AuthTokens") != NULL) {

		$objectsInstances = getFlickrObjectsInstances();

		$unSelectedSetsSide = "";
		$selectedSetsSide = "";
		$divSetsSelector = "";

		for($i = 0; $i < count($objectsInstances); $i++) {

			$f = $objectsInstances[$i];

			$owner = getOwner($f);

			$photosets = $f->photosets_getList(NULL, NULL, NULL, NULL);

			if(count($objectsInstances) > 1)
				$unSelectedSetsSide .= "<p class=\"account\">".getResource("fromAccount")." : <b><u>".$owner."</u></b>&nbsp;<input type=\"button\" value=\"".getResource("btnRemoveAccount")."\" onClick=\"javascript:this.form.accountIndex.value='$i';this.form.removeAccount.value=true;this.form.submit();\" /></p>";
			else
				$unSelectedSetsSide .= "<p class=\"account\">".getResource("fromAccount")." : <b><u>".$owner."</u></b>&nbsp;</p>";


			$unSelectedSetsSide .= "<select name=\"unSelectedSets".$i."[]\" multiple style=\"width: 300px\" size=\"10\" >";
			foreach ($photosets['photoset'] as $set) {
	  		$setId = $set['id'];
				if(substr_count(getTypedConf("SelectedSets"),$setId) == 0)
					$unSelectedSetsSide .= "<option value=\"$setId\">".$set['title']."</option>";
			}
			$unSelectedSetsSide .= "</select><p class=\"button\"><input type=\"button\" value=\"".getResource("btnAddToSelectSets")."\" onClick=\"javascript:this.form.accountIndex.value='$i';this.form.addSets.value=true;this.form.submit();\" /></p><hr />";

		}

		$unSelectedSetsSide .= "<p class=\"importantButton\"><input type=\"button\" value=\"".getResource("btnAddAnotherAccount")."\" onClick=\"javascript:this.form.addAnotherAccount.value=true;this.form.submit();\" />";


		$selectedSetsSide .= "<p class=\"subtitle\">".getResource("fromEveryAccount")." : </p>";
		$selectedSetsSide .= "<select name=\"selectedSets[]\" style=\"width: 300px\" multiple size=\"10\" >";

		$selectedSets = explode(",",getTypedConf("SelectedSets"));

		for($i = 0; $i < count($selectedSets); $i++) {

			$accountSet = explode(".",$selectedSets[$i]);
			if(count($accountSet) == 2) {

				$account = $accountSet[0];
				$set = $accountSet[1];
				$f = $objectsInstances[$account];
				$setInfo = $f->photosets_getInfo($set);
				if(substr_count(getTypedConf("DefaultSet"),$selectedSets[$i]) > 0)
			 		$selectedSetsSide .= "<option value=\"$selectedSets[$i]\">".$setInfo['title']." ".getResource("defaultSet")."</option>";
				else
					$selectedSetsSide .= "<option value=\"$selectedSets[$i]\">".$setInfo['title']."</option>";
			}

		}

		$selectedSetsSide .= "</select>";
		$selectedSetsSide .= "<p class=\"button\"><input type=\"button\" value=\"X\" onClick=\"javascript:this.form.removeSets.value=true;this.form.submit();\" /><input type=\"button\" value=\"&uarr;\" onClick=\"javascript:this.form.moveUp.value=true;this.form.submit();\" /><input type=\"button\" value=\"&darr;\" onClick=\"javascript:this.form.moveDown.value=true;this.form.submit();\" /><input type=\"button\" value=\"".getResource("btnSetAsDefault")."\" onClick=\"javascript:this.form.setDefault.value=true;this.form.submit();\" /><input type=\"button\" value=\"".getResource("btnClearDefault")."\" onClick=\"javascript:this.form.clearDefault.value=true;this.form.submit();\" /></p>";

		$selectedSetsSide .= "<p class=\"subtitle\">".getResource("explanationReinitCache")." : </p>";

		$selectedSetsSide .= "<input type=\"hidden\" name=\"reinitImagesCache\" value=\"false\" />";
		$selectedSetsSide .= "<p class=\"importantButton\"><input type=\"button\" onClick=\"javascript:this.form.reinitImagesCache.value=true;this.form.submit();\" value=\">>>> ".getResource("btnReinitImagesCache")." <<<<\" /></p>";

		$selectedSetsSide .= "<input type=\"hidden\" name=\"reinitSetsCache\" value=\"false\" />";
		$selectedSetsSide .= "<p class=\"importantButton\"><input type=\"button\" onClick=\"javascript:this.form.reinitSetsCache.value=true;this.form.submit();\" value=\">>>> ".getResource("btnReinitSetsCache")." <<<<\" /></p>";

		echo "<input type=\"hidden\" name=\"addSets\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"removeSets\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"setDefault\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"clearDefault\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"moveUp\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"moveDown\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"accountIndex\" value=\"-1\" />";
		echo "<input type=\"hidden\" name=\"addAnotherAccount\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"removeAccount\" value=\"false\" />";


		$divSetsSelector .= "<table width=\"800\" height=\"500\" cellpadding=\"15\" class=\"styled\"><tr height=\"8%\"><td width=\"50%\" align=\"center\"><p class=\"title\">".getResource("unselectedSets")." :</p></td><td width=\"50%\" align=\"center\"><p class=\"title\">".getResource("selectedSets")." :</p></td></tr><tr><td width=\"50%\" align=\"center\" valign=\"top\">";
		$divSetsSelector .= $unSelectedSetsSide;
		$divSetsSelector .= "</td><td width=\"50%\" align=\"center\" valign=\"top\">";
		$divSetsSelector .= $selectedSetsSide."</td></tr></table>";

		echo $divSetsSelector;


	}

}

// =======================
// = Reinit images cache =
// =======================
function reinitImagesCache() {

	$cacheDir = getTypedConf("CacheDir");

	if (file_exists($cacheDir)) {
	   $mydir = opendir($cacheDir);
	   while(false !== ($file = readdir($mydir))) {
	       if($file != "." && $file != "..") {
	           if(substr($file, -6) == ".cache") {
	            if(file_exists($cacheDir."/".$file))
					unlink($cacheDir."/".$file) or DIE(getResource("messageCouldntDelete")." $cacheDir/$file<br />");
	           }
	       }
	   }
	   closedir($mydir);
	   echo "<p class=\"succeed\">".getResource("messageCleanCacheSucceed")."</p>";
	}
}

// =========================
// = Reinit the Sets cache =
// =========================
function reinitSetsCache()
{
	$selectedSetsAsString = getTypedConf("SelectedSets");
	$selectedSets = explode(",",$selectedSetsAsString);
	$objectsInstances = getFlickrObjectsInstances();
	$selectedSetsTitles = array();
	$selectedSetsThumbnails = array();
	for($i = 0; $i < count($selectedSets); $i++) {
		$accountSet = explode(".",$selectedSets[$i]);

		if(count($accountSet) == 2) {
			$account = $accountSet[0];
			$set = $accountSet[1];
			$f = $objectsInstances[$account];
			$setInfo = $f->photosets_getInfo($set);
			array_push($selectedSetsTitles, $setInfo['title']);
			array_push($selectedSetsThumbnails, $f->buildPhotoURL($f->photos_getInfo($setInfo['primary'])["photo"], "square"));
		}
	}
	setTypedConf("SelectedSetsTitles", implode("@@", $selectedSetsTitles));
	setTypedConf("SelectedSetsThumbnails", implode("@@", $selectedSetsThumbnails));
}

function getFontFamilyForm($var,$name,$id,$onChangeFunction) {

	$families =  explode("|", FONT_FAMILIES);

	echo "
	<select name=\"$name\" id=\"$id\" onChange=\"javascript:$onChangeFunction;\">";

	foreach($families as $family)
		if($var == $family)
			echo "<option value=\"$family\" selected>$family</option>";
		else
			echo "<option value=\"$family\">$family</option>";

	echo "</select>";

}

function getFontSizeForm($var,$name,$id,$onChangeFunction) {

	$sizes = array(8,10,11,12,14,16,18,20,24,36,48,72);

	echo "
	<select name=\"$name\" id=\"$id\" onChange=\"javascript:$onChangeFunction;\">";

	foreach($sizes as $size)
		if($var == $size)
			echo "<option value=\"".$size."px\" selected>".$size."px</option>";
		else
			echo "<option value=\"".$size."px\">".$size."px</option>";

	echo "</select>";

}

function getFontStyleForm($var,$name,$id,$onChangeFunction) {

	$styles = array("normal","italic","oblique");

	echo "
	<select name=\"$name\" id=\"$id\" onChange=\"javascript:$onChangeFunction;\">";

	foreach($styles as $style)
		if($var == $style)
			echo "<option value=\"$style\" selected>$style</option>";
		else
			echo "<option value=\"$style\">$style</option>";

	echo "</select>";

}

function getFontWeightForm($var,$name,$id,$onChangeFunction) {

	$weights = array("normal","bold","bolder","lighter",100,200,300,400,500,600,700,800,900);

	echo "
	<select name=\"$name\" id=\"$id\" onChange=\"javascript:$onChangeFunction;\">";

	foreach($weights as $weight)
		if($var == $weight)
			echo "<option value=\"$weight\" selected>$weight</option>";
		else
			echo "<option value=\"$weight\">$weight</option>";

	echo "</select>";

}

function getTextDecorationForm($var,$name,$id,$onChangeFunction) {

	$decorations = array("none","underline","overline","line-through","blink","inherit");

	echo "
	<select name=\"$name\" id=\"$id\" onChange=\"javascript:$onChangeFunction;\">";

	foreach($decorations as $decoration)
		if($var == $decoration)
			echo "<option value=\"$decoration\" selected>$decoration</option>";
		else
			echo "<option value=\"$decoration\">$decoration</option>";

	echo "</select>";

}

function getTextAlignForm($var,$name,$id,$onChangeFunction) {

	$alignments = array("left","right","center","justify","inherit");

	echo "
	<select name=\"$name\" id=\"$id\" onChange=\"javascript:$onChangeFunction;\">";

	foreach($alignments as $alignment)
		if($var == $alignment)
			echo "<option value=\"$alignment\" selected>$alignment</option>";
		else
			echo "<option value=\"$alignment\">$alignment</option>";

	echo "</select>";

}

function getBorderWidthForm($var,$name,$id,$onChangeFunction) {

	$widths = array(0,1,2,3,4,5,6,7,8,9,10);

	echo "
	<select name=\"$name\" id=\"$id\" onChange=\"javascript:$onChangeFunction;\">";

	foreach($widths as $width)
		if($var == $width)
			echo "<option value=\"".$width."px\" selected>$width</option>";
		else
			echo "<option value=\"".$width."px\" >$width</option>";

	echo "</select>";

}

function getBorderStyleForm($var,$name,$id,$onChangeFunction) {

	$styles = array("none","dotted","dashed","solid","double","groove","ridge","inset","outset");

	echo "
	<select name=\"$name\" id=\"$id\" onChange=\"javascript:$onChangeFunction;\">";

	foreach($styles as $style)
		if($var == $style)
			echo "<option value=\"$style\" selected>$style</option>";
		else
			echo "<option value=\"$style\">$style</option>";

	echo "</select>";

}

// =============================
// = Export the specified file =
// =============================
function exportFile($path) {

	if ($fd = fopen ($path, "r")) {
	    $fsize = filesize($path);
	    $path_parts = pathinfo($path);
	    $ext = strtolower($path_parts["extension"]);
	    header("Content-type: text/plain");
	    header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
	    header("Content-length: $fsize");
		header("Content-Transfer-Encoding: binary");
		header("Cache-control: private");
		header('Pragma: private');
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	    while(!feof($fd)) {
	        $buffer = fread($fd, 2048);
	        echo $buffer;
	    }
	}
	fclose ($fd);
	exit;

}

// ==================================
// = Import old configuration =
// ==================================
function importConf($oldConfFilePath,$newConfFilePath) {

	$currentVersion = getTypedConf("Version");
	$oldVersion = getTypedConf("Version",$oldConfFilePath);
	if($oldVersion == NULL)
		$oldVersion = "1.0.0";

	if($oldVersion == "1.0.0") {

		$selectedSets = getTypedConf("SelectedSets",$oldConfFilePath);
		if($selectedSets != NULL) {
			$sets=explode(",",$selectedSets);
			$setTabs = NULL;
			foreach($sets as $set) {
				if(substr_count($set,".") <= 0)
					$setTabs[] = "0.".$set;
				else
					$setTabs[] = $set;
			}

			$newSets = "";
			for($i = 0; $i < count($setTabs); $i++) {
				if($i == 0)
					$newSets .= $setTabs[$i];
				else
					$newSets .= ",".$setTabs[$i];
			}

			setTypedConf("SelectedSets",$newSets,false,$oldConfFilePath);
			setUpSelectedSetsInfo();
		}
		$defautSet = getTypedConf("DefaultSet",$oldConfFilePath);
		if($defautSet != NULL)
			setTypedConf("DefaultSet","0.".$defautSet,false,$oldConfFilePath);
	}

	// retrieve changeLog matrix
	$matrix = NULL;
	if($currentVersion != $oldVersion) {
		$confChangeLog = "config/confChangeLog.txt";
		$confChangeLog = file ($confChangeLog);
		while (list ($lineNb, $line) = each ($confChangeLog)) {
			$line=trim($line);
			$config = explode(";",$line);
			if($config[1] == $oldVersion)
				$matrix[$config[0]] = $config[2];
		}
	}

	// backup file before modifying it
	$confFileBak = fopen($newConfFilePath.".bak", "w");
	fwrite($confFileBak, file_get_contents($newConfFilePath));
	fclose($confFileBak);

	// import configuration
	$newConfFilePath = file ($newConfFilePath);
	while (list ($lineNb, $line) = each ($newConfFilePath)) {
		if(mb_eregi("^\\$",trim($line))) {

			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);

			if($var != "\$strVersion") {

				$isList = false;
				$isFileList = false;
				$isNonTyped = false;

				if(substr_count($var,"\$str") > 0)
					$var = substr($var,4);
				else if(substr_count($var,"\$list") > 0) {
					$var = substr($var,5);
					$isList = true;
				} else if(substr_count($var,"\$bool") > 0)
					$var = substr($var,5);
				else if(substr_count($var,"\$n") > 0)
					$var = substr($var,2);
				else if(substr_count($var,"\$filelist") > 0) {
					$var = substr($var,9);
					$isFileList = true;
				} else {
					// such as CSS
					$isNonTyped = true;
					$var = substr($var,1);
				}

				$oldVar = $var;
				if($matrix != NULL && $matrix[$var] != NULL)
					$oldVar = $matrix[$var];

				if($isList && getListSelectedValue($oldVar,$oldConfFilePath) != NULL)
					setTypedConf($var,getListSelectedValue($oldVar,$oldConfFilePath));
				elseif($isFileList && getFileListSelectedValue($oldVar,$oldConfFilePath) != NULL)
					setTypedConf($var,getFileListSelectedValue($oldVar,$oldConfFilePath));
				elseif($isNonTyped && getConf($oldVar,$oldConfFilePath) != NULL)
					setConf($var,getConf($oldVar,$oldConfFilePath));
				elseif(getTypedConf($oldVar,$oldConfFilePath) != NULL)
					setTypedConf($var,getTypedConf($oldVar,$oldConfFilePath));
			}
		}
	}

}

// ==================================
// = Recursively remove a directory
// ==================================
function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file))
            rrmdir($file);
        else
            unlink($file) or DIE("couldn't delete file $file<br />");
    }
    rmdir($dir) or DIE("couldn't delete directory $dir<br />");
}

// ==================================
// = Update file content
// ==================================
function savefile($filepath,$filecontent) {
	$file = fopen($filepath, "w");
	fwrite($file, $filecontent);
	fclose($file);
}

// ==================================================================
// = Execute a PHP template file and return the result as a string
// ==================================================================
function apply_template($templateFile, $includeGlobals = true)
{

  if ($includeGlobals) extract($GLOBALS, EXTR_SKIP);

  ob_start();

  require($templateFile);

  $applied_template = ob_get_contents();
  ob_end_clean();

  return $applied_template;
}

?>
