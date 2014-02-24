show("","");

function getIndice(id) {

   for(var i=0;Mnlfolio.idPhotos.length;i++) {
    if(Mnlfolio.idPhotos[i] == id) {
     Mnlfolio.pos = i;
     return i;
    }
   }

}

//##################################//
//    ACTIONS : PREVIOUS / NEXT
//##################################//

function up() {
  upPhoto();
}

function down() {
  downPhoto();
}

function upPhoto() {
  var setPage = Mnlfolio.currentSetPage;
  var first = (setPage-1)*Mnlfolio.thumbsBySetPage;
  if((Mnlfolio.pos-columns) < 0) {
    return;
  }
  if((Mnlfolio.pos-columns) < first) {
    setPage = Mnlfolio.currentSetPage-1;
  }
  var last=((setPage)*Mnlfolio.thumbsBySetPage)-1;
  show(Mnlfolio.idPhotos[Mnlfolio.pos-columns],setPage);
}

function downPhoto() {
  var setPage = Mnlfolio.currentSetPage;
  var last=((setPage)*Mnlfolio.thumbsBySetPage)-1;
  if((Mnlfolio.pos+columns) > (Mnlfolio.idPhotos.length)-1) {
    return;
  }
  if((Mnlfolio.pos+columns) > last) {
    setPage = Mnlfolio.currentSetPage+1;
  }
  show(Mnlfolio.idPhotos[Mnlfolio.pos+columns],setPage);
}

function previousSetPage() { 
  var setPage = Mnlfolio.currentSetPage;
  if(Mnlfolio.currentSetPage > 1) {
    setPage = Mnlfolio.currentSetPage-1;
    var last=((setPage)*Mnlfolio.thumbsBySetPage)-1;
    show(Mnlfolio.idPhotos[last],setPage);
  }
}

function nextSetPage() {
  var setPage = Mnlfolio.currentSetPage;
   if(Mnlfolio.currentSetPage < Mnlfolio.totalSetPage) {
    setPage = Mnlfolio.currentSetPage+1;
    var first=(setPage-1)*Mnlfolio.thumbsBySetPage;
    show(Mnlfolio.idPhotos[first],setPage);
   }
}


function previousPhoto() {
  var setPage = Mnlfolio.currentSetPage;
  var photo = Mnlfolio.idPhotos[Mnlfolio.pos];
  if(Mnlfolio.pos > 0) {
    var first=(Mnlfolio.currentSetPage-1)*Mnlfolio.thumbsBySetPage;
    if(first == Mnlfolio.pos) {
      setPage = Mnlfolio.currentSetPage - 1;      
    }
    photo = Mnlfolio.idPhotos[Mnlfolio.pos-1];
  }
  show(photo,setPage);
}

function nextPhoto() {
  var setPage = Mnlfolio.currentSetPage;
  var photo = Mnlfolio.idPhotos[Mnlfolio.pos];
  if(Mnlfolio.pos < Mnlfolio.idPhotos.length-1) {
    var div=(Mnlfolio.pos+1)%Mnlfolio.thumbsBySetPage;
    if(div == 0) {
      setPage = Mnlfolio.currentSetPage + 1;
    }
    photo = Mnlfolio.idPhotos[Mnlfolio.pos+1];
  }
  show(photo,setPage);
}

//##################################//
//    GALLERY RETRIEVAL
//##################################//

function show(idPhoto,setPage)
{

  var showPreviousPhoto = "false";
  var showNextPhoto = "false";
  var showPreviousThumbnailsPage = "false";
  var showNextThumbnailsPage = "false";
  
  if(Mnlfolio.idPhotos.length > 0 && Mnlfolio.totalSetPage > 0) {

  if(Mnlfolio.pos == -1)
   idPhoto = Mnlfolio.idPhotos[0];

  Mnlfolio.pos = getIndice(idPhoto);

  if(Mnlfolio.currentSetPage == -1)
   Mnlfolio.currentSetPage = 1;

  if (setPage == "")
  Mnlfolio.currentSetPage = 1;
  else
    Mnlfolio.currentSetPage = setPage;

  
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

  if(Mnlfolio.pos > 0) 
    showPreviousPhoto = "true";
  if(Mnlfolio.pos < Mnlfolio.idPhotos.length-1) 
    showNextPhoto = "true";  

      
  if(Mnlfolio.currentSetPage > 1) 
    showPreviousThumbnailsPage = "true";
  if(Mnlfolio.currentSetPage < Mnlfolio.totalSetPage) 
    showNextThumbnailsPage = "true";    

  xmlhttp.open("GET","getGallery.php?photoid="+idPhoto+"&p="+showPreviousPhoto+"&n="+showNextPhoto+"&setPage="+Mnlfolio.currentSetPage+"&setId="+Mnlfolio.setId+"&u="+showPreviousThumbnailsPage+"&d="+showNextThumbnailsPage,true);
  xmlhttp.send();
 }
 
}


//##################################//
//    USER SETS RETRIEVAL
//##################################//

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

//##################################//
//    CONTROLS : ARROWS / MOUSE
//##################################//

function getArrows(event) {
  if(Mnlfolio.boolEnableKeyboardControl) {
    if(Mnlfolio.pos >= 0) {
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
  }
}


function handle(delta) {
  if (delta < 0)
    nextPhoto();
  else
    previousPhoto();
}
 
function wheel(event){
  if(Mnlfolio.boolEnableScrollWheelControl) {  
    var delta = 0;
    if (!event) {
      event = window.event;
    }
    if (event.wheelDelta) {
      delta = event.wheelDelta/120;
      if (window.opera) {
        delta = -delta;
      }
    } else if (event.detail) {
      delta = -event.detail/3;
    }
    if (delta) {
      handle(delta);
    }
  }    
}

function init() {
/* Initialization code.
 if (window.addEventListener)
  window.addEventListener('DOMMouseScroll', wheel, false);
  document.onmousewheel = wheel; */
  if(Mnlfolio.boolTweakSaveImageAs) {
    if(document.getElementById('gallery') != null) {
      document.oncontextmenu=new Function("return false");
    }
  }
}

function changeBackgroundColor(color) {
  var body = document.getElementById("body");
  
  if(Mnlfolio.currentBackgroundColor != color) {
    body.style.backgroundColor = color;
    Mnlfolio.currentBackgroundColor = color;
  } else {
    body.style.backgroundColor = '#' + Mnlfolio.mnlfbodybgcolor;
    Mnlfolio.currentBackgroundColor = '#' + Mnlfolio.mnlfbodybgcolor;
  }
}

if(Mnlfolio.boolTweakSaveImageAs) {
  window.onload=function () { 
    if(document.getElementById('image') != null) {
      document.oncontextmenu=new Function("return false");
    }
  }
}