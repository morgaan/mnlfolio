<?

//BASICPARAMETERS

//general
$listLanguage = "en|en,fr"; 

//pageParameters
//headerParams
$strPageTitle = "My mnlfolio"; 
$boolShowLogo=true; 
$filelistLogo = "logo-mnlfolio-medium.gif|design/images/logos";
$strLogoLink="index.php";
$boolShowNavigationMenu = true;
$strDelimiterSetsTitles = " &middot; "; 
$boolShowContact=true;
$strContactLabel="@"; 
$strContactEmail=""; 
$boolShowBackgroundColorPicker=true;

//galleryParams
$boolShowPhotoDescription=true; 
$boolShowPhotoTitle=true;

//guiParams
$listPhotoSize="medium|small,medium,large,original";
$nGUIWidth=600;
$nGUIHeight=600;
$nColumns=3; 
$nRows=4; 
$filelistNavigationLayout = "sets_titles_only|design/layouts/navigation"; 
$filelistViewerLayout = "thumbnails_on_left|design/layouts/viewer";

//footerParams
$boolShowCopyright=true; 
$strCopyright="(c) mnlfolio";
$boolShowMnlfolioPromo=true;
$boolShowAdminLink=true;

//ADVANCEDPARAMETERS

//mnlfolio admin account
$strUsername=""; 
$strPassword=""; 
$strPwdQuestion="";
$strPwdAnswer="";

//controls
$boolEnableKeyboardControl=true;
$boolEnableScrollWheelControl=false;
$boolTweakSaveImageAs=true; 
$strPreviousPhotoControlLabel="&larr;";
$strNextPhotoControlLabel="&rarr;";
$strPreviousThumbnailsPageControlLabel="&uarr;";
$strNextThumbnailsPageControlLabel="&darr;";

//webPromotion
$strMetaDescription="minimalist flickr-based portfolio";
$strMetaAuthor="mnlfolio";
$strMetaKeywords="mnlfolio,portfolio,book,photobook";

//techParams
$strCacheDir="cache"; 
$nCacheTimeToLive=2592000; 

//flickrParams
$strSelectedSets=""; 
$strDefaultSet="";
$urlApiKeyGenerator="http://www.flickr.com/services/apps/create/apply";
$strApiKey=""; 
$strApiSecret="";
$strPerms="read";
$strAuthTokens="";

$strVersion="1.1.0";

?>