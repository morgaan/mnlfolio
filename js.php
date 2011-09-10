<?php
/*
 *	mnlfolio v1.5.2
 *	by Morgan Cugerone - http://ipositives.net
 *	Last Modification: 20110906
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

 require_once("config/css.php");

?>

<script type="text/javascript">

 var idPhotos = new Array();
 var photosThumbsPages = new Array();
 var descPhotos = new Array();
 var pos = -1;
 var setSize = -1;
 var currentSetPage = -1;
 var totalSetPage = -1;
 var setId = -1;
 var thumbsBySetPage = 1;
 var currentBackgroundColor =  '';


<?

 $thumbsBySetPage = ($nRows*$nColumns);
 echo "var thumbsBySetPage ='$thumbsBySetPage';";
 
  echo "var rows = $nRows;";
  echo "var columns = $nColumns;";
 
if (($boolSetIdByPost && !empty($_POST['setid'])) || (!$boolSetIdByPost && isset($setid)) ) {

		if($boolSetIdByPost)
		   $setId = $_POST['setid'];
		else
		   $setId = $setid;

		$accountSet = explode(".",$setId);

		if(count($accountSet) == 2) {

			  $account = $accountSet[0];
			  $set = $accountSet[1];

		  	  $f = $objectsInstances[$account];
		   
	 		  echo "setId='$setId';";

			  $photos = $f->photosets_getPhotos($set);
		 
			  // Loop through the photos and output the html
			  $i=0;
	    	  foreach ($photos['photoset']['photo'] as $photo) {
    	  	
	    	  	$photoInfo = $f->photos_getInfo($photo['id'],NULL);
  			
	  			echo "idPhotos[$i]=\"".$photo['id']."\";";
  			
	  			$i++;
	      	  }
      	 
	 		  $totalPage = ceil($i/($nColumns*$nRows));
 		 
	 		  echo "setSize=$i;";
	      	  echo "totalSetPage=$totalPage;";
 		}
}

?>

show("","");


function getIndice(id) {

   for(var i=0;idPhotos.length;i++) {
    if(idPhotos[i] == id) {
     pos = i;
     return i;
    }
   }

}


<?
//##################################//
//		ACTIONS : PREVIOUS / NEXT
//##################################//
?>

function up() {

<?
//if($strUpdown == "set") {
?>
<!--	previousSetPage();-->
<?
//}

//elseif($strUpdown == "photo") {
?>
	upPhoto();
<?
//}
?>

}



function down() {

<?
//if($strUpdown == "set") {
?>
<!--	nextSetPage();-->
<?
//}

//elseif($strUpdown == "photo") {
?>
	downPhoto(); 
<?
//}
?>

}


function upPhoto() {
 
 var setPage = currentSetPage;
 var first=(setPage-1)*thumbsBySetPage;
 
 if((pos-columns) < 0)  return;
 
 if((pos-columns) < first) 
    setPage = currentSetPage-1;
 
 	var last=((setPage)*thumbsBySetPage)-1;
 
 	show(idPhotos[pos-columns],setPage);
 
 
}

function downPhoto() {

   var setPage = currentSetPage;
   var last=((setPage)*thumbsBySetPage)-1;

   if((pos+columns) > (idPhotos.length)-1) return;
	
   if((pos+columns) > last)
    setPage = currentSetPage+1;
	
   show(idPhotos[pos+columns],setPage);


}



function previousSetPage() {
 
 var setPage = currentSetPage;
 
 if(currentSetPage > 1) {
    setPage = currentSetPage-1;
 
 	var last=((setPage)*thumbsBySetPage)-1;
 
 	show(idPhotos[last],setPage);
 }
 
}

function nextSetPage() {

	var setPage = currentSetPage;

   if(currentSetPage < totalSetPage) {
    setPage = currentSetPage+1;
    
   	var first=(setPage-1)*thumbsBySetPage;
	
   	show(idPhotos[first],setPage);
   	
   }

}


function previousPhoto() {

 var setPage = currentSetPage;
 var photo = idPhotos[pos];

 if(pos > 0) {

 	 var first=(currentSetPage-1)*thumbsBySetPage;

 	 if(first == pos) {
      setPage = currentSetPage - 1;      
     }
     
      photo = idPhotos[pos-1];
    
 }

 show(photo,setPage);
 
}

function nextPhoto() {

 var setPage = currentSetPage;
 var photo = idPhotos[pos];


   if(pos < idPhotos.length-1) {
   
     var div=(pos+1)%thumbsBySetPage;
     
   	 if(div == 0) {
   	  setPage = currentSetPage + 1;
     }
     
      photo = idPhotos[pos+1];

   }

   show(photo,setPage);

}

<?
//##################################//
//  	GALLERY RETRIEVAL
//##################################//
?>

function show(idPhoto,setPage)
{

  var showPreviousPhoto = "false";
  var showNextPhoto = "false";
  var showPreviousThumbnailsPage = "false";
  var showNextThumbnailsPage = "false";
  
  if(idPhotos.length > 0 && totalSetPage > 0) {

  if(pos == -1)
   idPhoto = idPhotos[0];

  pos = getIndice(idPhoto);

  if(currentSetPage == -1)
   currentSetPage = 1;

  if (setPage == "")
	currentSetPage = 1;
  else
    currentSetPage = setPage;

  
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
  }
  else
  {// code for IE6, IE5
   xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  
  xmlhttp.onreadystatechange=function()
  {

    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {   
      document.getElementById("gallery").innerHTML=xmlhttp.responseText;
    }
  }

  if(pos > 0) 
    showPreviousPhoto = "true";
  if(pos < idPhotos.length-1) 
    showNextPhoto = "true";  

      
  if(currentSetPage > 1) 
    showPreviousThumbnailsPage = "true";
  if(currentSetPage < totalSetPage) 
    showNextThumbnailsPage = "true";    

  xmlhttp.open("GET","getGallery.php?photoid="+idPhoto+"&p="+showPreviousPhoto+"&n="+showNextPhoto+"&setPage="+currentSetPage+"&setId="+setId+"&u="+showPreviousThumbnailsPage+"&d="+showNextThumbnailsPage,true);
  xmlhttp.send();
 }
 
}


<?
//##################################//
//  	USER SETS RETRIEVAL
//##################################//
?>

function getSets(form)
{

  if (form.username.selectedIndex != 0)
  {

  	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
   		xmlhttp=new XMLHttpRequest();
  	}
  	else
  	{// code for IE6, IE5
   		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
  
  	xmlhttp.onreadystatechange=function()
  	{

    	if (xmlhttp.readyState==4 && xmlhttp.status==200)
    	{
      		document.getElementById("setchoice").innerHTML=xmlhttp.responseText;
    	}
  	} 
  	

  	xmlhttp.open("GET","getSets.php?username="+form.username.options[form.username.selectedIndex].value,true);
  	xmlhttp.send();

  }
  
}

<?
//##################################//
//		CONTROLS : ARROWS / MOUSE
//##################################//
?>

function getArrows(event) {

<?

	if($boolEnableKeyboardControl) {

?>

 if(pos >= 0) {

  arrows=((event.which)||(event.keyCode));

switch(arrows) {

 // left & up
 case 37:
   event.preventDefault();
   previousPhoto()
   break; 
 
 case 38:
   event.preventDefault();
   up()
   break;
   
 // right & down
 case 39:
  event.preventDefault();
  nextPhoto();
  break; 
 
 case 40:
  event.preventDefault();
  down();
  break;

 }
  
  }

<?

	}

?>

 }


function handle(delta) {
	if (delta < 0)
		nextPhoto();
	else
		previousPhoto();
}
 
function wheel(event){
	
<?

	if($boolEnableScrollWheelControl) {

?>	
	
	var delta = 0;
	if (!event) event = window.event;
	if (event.wheelDelta) {
		delta = event.wheelDelta/120;
		if (window.opera) delta = -delta;
	} else if (event.detail) {
		delta = -event.detail/3;
	}
	if (delta)
		handle(delta);
		
<?

	}

?>
		
}

function init() {
/* Initialization code.
 if (window.addEventListener)
	window.addEventListener('DOMMouseScroll', wheel, false);
  document.onmousewheel = wheel; */

<?
	if($boolTweakSaveImageAs) {
?>

	if(document.getElementById('gallery') != null) {
	 document.oncontextmenu=new Function("return false");
	}

<?
	}
?>

}

function changeBackgroundColor(color) {
	var body = document.getElementById("body");
	
	if(currentBackgroundColor != color) {
		body.style.backgroundColor = color;
		currentBackgroundColor = color;
	} else {
		body.style.backgroundColor = <? echo "'#$mnlfbodybgcolor'"; ?>;
		currentBackgroundColor = <? echo "'#$mnlfbodybgcolor'"; ?>;
	}
}

<?
	if($boolTweakSaveImageAs) {
?>

		window.onload=function () { 
			if(document.getElementById('image') != null) {
				document.oncontextmenu=new Function("return false");
			}
		}

<?
	}
?>

</script>


