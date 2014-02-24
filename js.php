<?
/*
 *  mnlfolio v1.5.4
 *  by Morgan Cugerone - http://ipositives.net
 *  Last Modification: 20140224
 *
 *  For more information, visit:
 *  http://morgan.cugerone.com/mnlfolio
 *
 *  Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
 *    - Free for use in both personal and commercial projects
 *    - Attribution requires leaving author name, author link, and the license info intact.
 *  
 *  Thanks: Jan Odvarko (http://odvarko.cz) for developing this wonderful piece of jscolor code
 *      Dan Coulter (dan@dancoulter.com / http://phpflickr.com) for bringing this great phpflickr interface
 *      To every friends and relatives who supported and helped me in the achievement of this project.
 */
?>

Mnlfolio.thumbsBySetPage = <? echo ($nRows*$nColumns); ?>;
Mnlfolio.rows = <? echo $nRows; ?>;
Mnlfolio.columns = <? echo $nColumns; ?>;

<?
if(isset($boolSetIdByPost) && $boolSetIdByPost && !empty($_POST['setid']))
   $setId = $_POST['setid'];
else if(!empty($_GET['setid']))
   $setId = $_GET['setid'];

if (isset($setId)) {

  $accountSet = explode(".",$setId);

  if(count($accountSet) == 2) {

      $account = $accountSet[0];
      $set = $accountSet[1];

        $f = $objectsInstances[$account];
?>     
Mnlfolio.setId='<? echo $setId; ?>';
<?
      $photos = $f->photosets_getPhotos($set);
   
      // Loop through the photos and output the html
      $i=0;
      foreach ($photos['photoset']['photo'] as $photo) {
        
        $photoInfo = $f->photos_getInfo($photo['id'],NULL);
?>
Mnlfolio.idPhotos[<? echo $i; ?>] = '<? echo $photo['id'] ?>';
<?
        $i++;
      }
       
      $totalPage = ceil($i/($nColumns*$nRows));

?>
Mnlfolio.setSize = <? echo $i; ?>;
Mnlfolio.totalSetPage = <? echo $totalPage; ?>;
<?
    }
}
?>

Mnlfolio.boolEnableKeyboardControl = <? echo $nColumns; ?>;
Mnlfolio.boolEnableScrollWheelControl = <? echo isset($boolEnableScrollWheelControl) ? "false" : "false"; ?>;
Mnlfolio.boolTweakSaveImageAs = <? echo $boolTweakSaveImageAs; ?>;
Mnlfolio.mnlfbodybgcolor =  <? echo $boolTweakSaveImageAs; ?>;