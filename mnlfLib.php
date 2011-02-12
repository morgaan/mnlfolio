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

require_once("phpFlickr.php");
require_once("config/mnlfConfig.php");


// =============================================
// = Designs the appropriate navigation layout =
// =============================================
function getNavigationLayout($setid = NULL) {

	$layout = "";
	
	if(getConf("AuthToken") != NULL && getConf("ShowNavigationMenu") == "true") {

		$f = getFlickrObjectInstance();
		$photosets = $f->photosets_getList();
		$delimiter = getConf("DelimiterSetsTitles");		
		$activeSet = "";

		foreach ($photosets['photoset'] as $set) {
			$setId = $set['id'];
			if(isset($setId) && $setid == $set['id']) {
				$activeSet = $set;
				break;
			}
		}

		include("design/layouts/navigation/".getFileListSelectedValue("NavigationLayout"));

	}

	return $layout;

}

// =========================================
// = Designs the appropriate viewer layout =
// =========================================
function getViewerLayout($setId, $setPage, $photoid, $showPreviousPhoto, $showNextPhoto, $showPreviousThumbnailsPage, $showNextThumbnailsPage) {

	$layout = "";

	if(strpos(getConf("SelectedSets"), $setId) !== false) {
	  	$f = getFlickrObjectInstance(true);
 
	  	$photoInfo = $f->photos_getInfo($photoid,NULL);
		$photosize = $f->photos_getSizes($photoid, $secret = NULL);
		$photoFormat = getListSelectedValue("PhotoSize");
		$zoomedPhotoFormat = getListSelectedValue("ZoomedPhotoSize");
	
		$size = $photosize[3];
	
		$photoDescription = "";
		$photoTitle = "";
		if(getConf("ShowPhotoDescription") == "true")
			$photoDescription = $photoInfo['description'];
		if(getConf("ShowPhotoTitle") == "true")
			$photoTitle = $photoInfo['title'];

		require("config/css.php");
		$borderDiv = str_replace("px","",$mnlfdivphotoborderwidth);
		$borderImg = str_replace("px","",$mnlfimgphotoborderwidth);
	    $tweakSaveImageAs = getConf("TweakSaveImageAs");
		$widthImg = $size[width]-($borderImg+$borderDiv);
		$heightImg = $size[height]-($borderImg+$borderDiv);

		$nColumns = getConf("Columns");
		$nRows = getConf("Rows");
	
		$previousPhotoControlLabel = getConf("PreviousPhotoControlLabel");
		$nextPhotoControlLabel = getConf("NextPhotoControlLabel");
		$previousThumbnailsPageControlLabel = getConf("PreviousThumbnailsPageControlLabel");
		$nextThumbnailsPageControlLabel = getConf("NextThumbnailsPageControlLabel");

		 $photosThumbs = $f->photosets_getPhotos($setId,NULL, "public", ($nColumns*$nRows), $setPage, NULL);
		 $i = 0;

		$nGUIWidth = getConf("GUIWidth");
		$nGUIHeight = getConf("GUIHeight");

		include("design/layouts/viewer/".getFileListSelectedValue("ViewerLayout"));
	
	}
	
	return $layout;

}


// ===============================================================
// = Store the token returned by Flickr API into the config File =
// ===============================================================
function setFrob($frob) {
	
	$f = getFlickrObjectInstance();
	$token = $f->auth_getToken ($frob);
	setConf("AuthToken",$token["token"]);
	
}


function getFlickrObjectInstance($enableCache = false) {

	$apiKey = getConf("ApiKey");
	$apiSecret = getConf("ApiSecret");
	$authToken = getConf("AuthToken");
	$cacheDir = getConf("CacheDir");
	$cacheTimeToLive = getConf("CacheTimeToLive");
	
	if($apiKey != NULL && $apiSecret != NULL) {
		$f = new phpFlickr($apiKey,$apiSecret);
		if($authToken != NULL)
			$f->setToken($authToken);
		if($enableCache == true && $cacheDir != NULL && $cacheTimeToLive != NULL)
			$f->enableCache("fs", $cacheDir, $cacheTimeToLive);
		
		return $f;
	}		
	else
		return NULL;
	
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
function getListSelectedValue($attribute){

	$configFile = "config/mnlfConfig.php";	
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(eregi("^\\$",trim($line))) {
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
function getListValues($attribute){

	$configFile = "config/mnlfConfig.php";	
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(eregi("^\\$",trim($line))) {
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
function getFileListSelectedValue($attribute){

	$configFile = "config/mnlfConfig.php";	
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(eregi("^\\$",trim($line))) {
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
function getFileListValues($attribute){

	$configFile = "config/mnlfConfig.php";	
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(eregi("^\\$",trim($line))) {
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
				        if($file != "." && $file != "..") {
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
	
	if(!isset($_SESSION["lang"]) && getConf("lang") == NULL)
		$_SESSION["lang"] = getListSelectedValue("Language");

	$configFile = "design/resources/resources-".$_SESSION["lang"].".php";
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			$var = substr($var,1,strlen($var)-1);
			$val = substr(trim($varVal[1]),0,-1);
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


// ==================================
// = Gets a configuration attribute =
// ==================================
function getConf($attribute){

	$configFile = "config/mnlfConfig.php";	
	$confFile = file ($configFile);
	while (list ($lineNb, $line) = each ($confFile)) {
		if(eregi("^\\$",trim($line))) {
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

// ==================================
// = Sets a configuration attribute =
// ==================================
function setConf($attribute,$value,$encrypt=false){

	$configFile = "config/mnlfConfig.php";
	
	$previousConfFile = file ($configFile);
	$confFileBak = fopen($configFile.".bak", "w");
	fwrite($confFileBak, file_get_contents($configFile));
	fclose($confFileBak);
	
	$newContent = "";
	while (list ($lineNb, $line) = each ($previousConfFile)) {

		if(eregi("^\\$",trim($line))) {
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

	if(getConf($attribute) == $value)
		return true;
	else
		return false;

}


// ======================================
// = Sets a css configuration attribute =
// ======================================
function setCSSConf($attribute,$value){

	$configFile = "config/css.php";
	
	$previousConfFile = file ($configFile);
	$confFileBak = fopen($configFile.".bak", "w");
	fwrite($confFileBak, file_get_contents($configFile));
	fclose($confFileBak);
	
	$newContent = "";
	while (list ($lineNb, $line) = each ($previousConfFile)) {

		if(eregi("^\\$",trim($line))) {
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

	if(getConf($attribute) == $value)
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
		if(eregi("^\\$",trim($line))) {
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
			if(count($valDesc) > 1)
				$desc = $valDesc[1];

			if($var != "" && $var != "\$strUsername"
					&& $var != "\$strPassword"  && $var != "\$strPwdQuestion" && $var != "\$strPwdAnswer" && $var != "\$strApi_sig"
						&& $var != "\$strAuthUrl" && $var != "\$strAuthToken"
							&& $var != "\$strPerms" && $var != "\$strFrob"
								&& $var != "\$strSelectedSets" && $var != "\$strDefaultSet" && $var != "\$strCacheDir"
								 	&& $var != "\$strApiKey" && $var != "\$strApiSecret" && $var != "\$strVersion") {

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
					<td><input type="text" size="50" <? echo "name=\"".getDispVar($var)."\""; ?> <? echo "value=\"".$val."\""; ?> /></td>
<?					
					}
?>					
					<td><? echo $desc; ?></td>
				</tr>
<?
			}		
			
		} elseif(eregi("^\\//",trim($line))) {
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
				<td colspan="3" align="right"><br /><input type="submit" <? echo "value=\"".getResource("btnSave")."\""; ?>></td>
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
		if(eregi("^\\$",trim($line))) {
			$varVal = explode("=",trim($line));
			$var = trim($varVal[0]);
			if($var != "" && $var != "\$strUsername" && $var != "\$strPassword" && $var != "\$strPwdQuestion" && $var != "\$strPwdAnswer" && $var != "\$strApi_sig" && $var != "\$strAuthUrl" && $var != "\$strAuthToken" && $var != "\$strPerms" && $var != "\$strFrob" && $var != "\$strSelectedSets" && $var != "\$strDefaultSet" && $var != "\$strCacheDir") {
				if(isset($_POST[getDispVar($var)]))
					setConf(getDispVar($var),trim($_POST[getDispVar($var)]));
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
function getUploader($targetDirectory, $action, $object, $uploadFieldLabel, $uploadButtonLabel, $deleteLabel, $extensionFilter = NULL) {
	
	echo "<br/><br/><table bgcolor=\"EEEEEE\"><tr><td align=\"center\">";
	echo "<input type=\"hidden\" name=\"uploadFile\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"removeFile\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"targetDirectory\" value=\"$targetDirectory\" />";
	echo "<h3>$uploadFieldLabel :</h3><input name=\"uploadedFile\" type=\"file\" /><br/>";
	echo "<input type=\"button\" value=\"$uploadButtonLabel\" onClick=\"javascript:this.form.uploadFile.value=true;this.form.submit();\" />";

	echo "<br/><h3>$deleteLabel :</h3>";
	echo "<select name=\"selectedFiles[]\" multiple >";

	if (file_exists($targetDirectory)) {
	    $mydir = opendir($targetDirectory);
	    while(false !== ($file = readdir($mydir))) {
	        if($file != "." && $file != "..") {
				echo "<option value=\"$file\">$file</option>";
	        }
	    }
		closedir($mydir);
	}
	echo "</select><br/>";
	echo "<input type=\"button\" value=\"".getResource("btnDelete")."\" onClick=\"javascript:this.form.removeFile.value=true;this.form.submit();\" /></td></tr></table>";
	
}

// ==========================
// = Displays the More Form =
// ==========================
function getMoreForm() {

	echo "<input type=\"hidden\" name=\"resetAll\" value=\"false\" />";
	echo "<input type=\"hidden\" name=\"resetFlickr\" value=\"false\" />";

	echo "<br/><br>";
	echo "<table class=\"normal\" width=\"600\" cellspacing=\"0\" cellpadding=\"4\" border=\"1\" bordercolor=\"#51555C\"><tr><td>";
	echo getResource("configResetAll");
	echo "</td><td align=\"center\" valign=\"middle\"><input type=\"button\" value=\"".getResource("btnResetAll")."\" onClick=\"javascript:this.form.resetAll.value=true;this.form.submit();\" /></td></tr><td>";
	echo getResource("configResetFlickrLink");
	echo "</td><td align=\"center\" valign=\"middle\"><input type=\"button\" value=\"".getResource("btnResetFlickrLink")."\" onClick=\"javascript:this.form.resetFlickr.value=true;this.form.submit();\" /></td></tr></td><td>";
	echo getResource("configApiKey");
	echo "</td><td align=\"center\" valign=\"middle\">".getConf("ApiKey")."</td></tr><td>";
	echo getResource("configApiSecret");
	echo "</td><td align=\"center\" valign=\"middle\">".getConf("ApiSecret")."</td></tr>";
	echo "</table>";
	
}

// =======================================
// = Displays all the sets as a dropdown =
// =======================================
function getSetsSelector() {
	
	if(getConf("AuthToken") != NULL) {

		$f = getFlickrObjectInstance();

		$photosets = $f->photosets_getList();

		echo "<input type=\"hidden\" name=\"addSets\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"removeSets\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"setDefault\" value=\"false\" />";
		echo "<input type=\"hidden\" name=\"clearDefault\" value=\"false\" />";

		echo "<br/><br/><table><tr><td align=\"center\">".getResource("unselectedSets")."</td><td></td><td align=\"center\">".getResource("selectedSets")."</td></tr><tr>";
		
		echo "<td><select name=\"unSelectedSets[]\" multiple width=\"100%\" style=\"width: 100%\" size=\"10\" >";
		foreach ($photosets['photoset'] as $set) {
  		$setId = $set['id'];
			if(substr_count(getConf("SelectedSets"),$setId) == 0)
				echo "<option value=\"$setId\">".$set['title']."</option>";
		}
		echo "</select><br/><br/></td><td>";
		
		echo "<input type=\"button\" value=\">>\" onClick=\"javascript:this.form.addSets.value=true;this.form.submit();\" /><br/><br/>";		
		echo "<input type=\"button\" value=\"<<\" onClick=\"javascript:this.form.removeSets.value=true;this.form.submit();\" /></td>";

		echo "<td align=\"center\"><select name=\"selectedSets[]\" width=\"100%\" style=\"width: 100%\" multiple size=\"10\" >";
		foreach ($photosets['photoset'] as $set) {
  		$setId = $set['id'];
			if(substr_count(getConf("SelectedSets"),$setId) > 0 && substr_count(getConf("DefaultSet"),$setId) > 0)
			 echo "<option value=\"$setId\">".$set['title']." (default)</option>";
			elseif(substr_count(getConf("SelectedSets"),$setId) > 0)
				echo "<option value=\"$setId\">".$set['title']."</option>";
		}
		echo "</select><br/><input type=\"button\" value=\"".getResource("btnSetAsDefault")."\" onClick=\"javascript:this.form.setDefault.value=true;this.form.submit();\" /><input type=\"button\" value=\"".getResource("btnClearDefault")."\" onClick=\"javascript:this.form.clearDefault.value=true;this.form.submit();\" /></td></tr></table>";


	}
	
}

// ===============
// = Reset cache =
// ===============
function resetCache() {
	
	$cacheDir = getConf("CacheDir");
	
	if (file_exists($cacheDir)) {
	   $mydir = opendir($cacheDir);
	   while(false !== ($file = readdir($mydir))) {
	       if($file != "." && $file != "..") {
	           if(substr($file, -6) == ".cache") {
	            unlink($cacheDir."/".$file) or DIE(getResource("messageCouldntDelete")." $cacheDir/$file<br />");
	           }
	       }
	   }
	   closedir($mydir);
	   echo "<p class=\"succeed\">".getResource("messageCleanCacheSucceed")."</p>";
	}
}

function getFontFamilyForm($var,$name,$id,$onChangeFunction) {
	
	$families = array("Arial","Arial Black","Comic Sans MS","Courier New","Georgia","Impact","Symbol","Times New Roman","Trebuchet MS","Verdana");
	
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

?>