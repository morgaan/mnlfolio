<?php
/*
 *	mnlfolio v1.5.4
 *	by Morgan Cugerone - http://ipositives.net
 *	Last Modification: 20140224
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

include("config/css.php");

?>

<script language="javascript">

function UpdatePreview() {

	var previewbody = document.getElementById("previewbody");
	var previewtext = document.getElementById("previewtext");
	var previewlogo = document.getElementById("previewlogo");
	var previewtitle = document.getElementById("previewtitle");
	var previewlinks = document.getElementById("previewlinks");
	var previewcontact = document.getElementById("previewcontact");
	var previewcopyright = document.getElementById("previewcopyright");	
	var previewphototitle = document.getElementById("previewphototitle");
	var previewphotodescription = document.getElementById("previewphotodescription");
	var previewphotonavigationcontrols = document.getElementById("previewphotonavigationcontrols");
	var previewthumbnailsnavigationcontrols = document.getElementById("previewthumbnailsnavigationcontrols");
	var previewtdthumb = document.getElementById("previewtdthumb");
	var previewtdthumbmouseover = document.getElementById("previewtdthumbmouseover");
	var previewtdthumbselected = document.getElementById("previewtdthumbselected");
	var previewimgthumb = document.getElementById("previewimgthumb");
	var previewimgthumbselected = document.getElementById("previewimgthumbselected");	
	var previewdivphoto = document.getElementById("previewdivphoto");
	var previewimgphoto = document.getElementById("previewimgphoto");
	var previewimgthumbhover = document.getElementById("previewimgthumbhover");
	var previewtdthumbhover = document.getElementById("previewtdthumbhover");

	previewlinks.style.fontFamily = mnlflinksfontfamily.options[mnlflinksfontfamily.selectedIndex].text;
	previewlinks.style.fontSize = mnlflinksfontsize.options[mnlflinksfontsize.selectedIndex].text; 
	previewlinks.style.fontWeight = mnlflinksfontweight.options[mnlflinksfontweight.selectedIndex].text;
	previewlinks.style.fontStyle = mnlflinksfontstyle.options[mnlflinksfontstyle.selectedIndex].text;
	previewlinks.style.textDecoration = mnlflinkstextdecoration.options[mnlflinkstextdecoration.selectedIndex].text;
	previewlinks.style.textAlign = mnlflinkstextalign.value;
	previewlinks.style.color = mnlflinksfontcolor.value;

	previewlogo.style.fontFamily = mnlflogofontfamily.options[mnlflogofontfamily.selectedIndex].text;
	previewlogo.style.fontSize = mnlflogofontsize.options[mnlflogofontsize.selectedIndex].text; 
	previewlogo.style.fontWeight = mnlflogofontweight.options[mnlflogofontweight.selectedIndex].text;
	previewlogo.style.fontStyle = mnlflogofontstyle.options[mnlflogofontstyle.selectedIndex].text;
	previewlogo.style.textDecoration = mnlflogotextdecoration.options[mnlflogotextdecoration.selectedIndex].text;
	previewlogo.style.textAlign = mnlflogotextalign.value;
	previewlogo.style.color = mnlflogofontcolor.value;

	previewtitle.style.fontFamily = mnlftitlefontfamily.options[mnlftitlefontfamily.selectedIndex].text;
	previewtitle.style.fontSize = mnlftitlefontsize.options[mnlftitlefontsize.selectedIndex].text; 
	previewtitle.style.fontWeight = mnlftitlefontweight.options[mnlftitlefontweight.selectedIndex].text;
	previewtitle.style.fontStyle = mnlftitlefontstyle.options[mnlftitlefontstyle.selectedIndex].text;
	previewtitle.style.textDecoration = mnlftitletextdecoration.options[mnlftitletextdecoration.selectedIndex].text;
	previewtitle.style.textAlign = mnlftitletextalign.value;
	previewtitle.style.color = mnlftitlefontcolor.value;
	
	previewcontact.style.fontFamily = mnlfcontactfontfamily.options[mnlfcontactfontfamily.selectedIndex].text;
	previewcontact.style.fontSize = mnlfcontactfontsize.options[mnlfcontactfontsize.selectedIndex].text; 
	previewcontact.style.fontWeight = mnlfcontactfontweight.options[mnlfcontactfontweight.selectedIndex].text;
	previewcontact.style.fontStyle = mnlfcontactfontstyle.options[mnlfcontactfontstyle.selectedIndex].text;
	previewcontact.style.textDecoration = mnlfcontacttextdecoration.options[mnlfcontacttextdecoration.selectedIndex].text;
	previewcontact.style.textAlign = mnlfcontacttextalign.value;
	previewcontact.style.color = mnlfcontactfontcolor.value;
	
	previewcopyright.style.fontFamily = mnlfcopyrightfontfamily.options[mnlfcopyrightfontfamily.selectedIndex].text;
	previewcopyright.style.fontSize = mnlfcopyrightfontsize.options[mnlfcopyrightfontsize.selectedIndex].text; 
	previewcopyright.style.fontWeight = mnlfcopyrightfontweight.options[mnlfcopyrightfontweight.selectedIndex].text;
	previewcopyright.style.fontStyle = mnlfcopyrightfontstyle.options[mnlfcopyrightfontstyle.selectedIndex].text;
	previewcopyright.style.textDecoration = mnlfcopyrighttextdecoration.options[mnlfcopyrighttextdecoration.selectedIndex].text;
	previewcopyright.style.textAlign = mnlfcopyrighttextalign.value;
	previewcopyright.style.color = mnlfcopyrightfontcolor.value;

	previewphototitle.style.fontFamily = mnlfphototitlefontfamily.options[mnlfphototitlefontfamily.selectedIndex].text;
	previewphototitle.style.fontSize = mnlfphototitlefontsize.options[mnlfphototitlefontsize.selectedIndex].text; 
	previewphototitle.style.fontWeight = mnlfphototitlefontweight.options[mnlfphototitlefontweight.selectedIndex].text;
	previewphototitle.style.fontStyle = mnlfphototitlefontstyle.options[mnlfphototitlefontstyle.selectedIndex].text;
	previewphototitle.style.textDecoration = mnlfphototitletextdecoration.options[mnlfphototitletextdecoration.selectedIndex].text;
	previewphototitle.style.textAlign = mnlfphototitletextalign.value;
	previewphototitle.style.color = mnlfphototitlefontcolor.value;

	previewphotodescription.style.fontFamily = mnlfphotodescriptionfontfamily.options[mnlfphotodescriptionfontfamily.selectedIndex].text;
	previewphotodescription.style.fontSize = mnlfphotodescriptionfontsize.options[mnlfphotodescriptionfontsize.selectedIndex].text; 
	previewphotodescription.style.fontWeight = mnlfphotodescriptionfontweight.options[mnlfphotodescriptionfontweight.selectedIndex].text;
	previewphotodescription.style.fontStyle = mnlfphotodescriptionfontstyle.options[mnlfphotodescriptionfontstyle.selectedIndex].text;
	previewphotodescription.style.textDecoration = mnlfphotodescriptiontextdecoration.options[mnlfphotodescriptiontextdecoration.selectedIndex].text;
	previewphotodescription.style.textAlign = mnlfphotodescriptiontextalign.value;
	previewphotodescription.style.color = mnlfphotodescriptionfontcolor.value;

	previewphotonavigationcontrols.style.fontFamily = mnlfphotonavigationcontrolsfontfamily.options[mnlfphotonavigationcontrolsfontfamily.selectedIndex].text;
	previewphotonavigationcontrols.style.fontSize = mnlfphotonavigationcontrolsfontsize.options[mnlfphotonavigationcontrolsfontsize.selectedIndex].text; 
	previewphotonavigationcontrols.style.fontWeight = mnlfphotonavigationcontrolsfontweight.options[mnlfphotonavigationcontrolsfontweight.selectedIndex].text;
	previewphotonavigationcontrols.style.fontStyle = mnlfphotonavigationcontrolsfontstyle.options[mnlfphotonavigationcontrolsfontstyle.selectedIndex].text;
	previewphotonavigationcontrols.style.textDecoration = mnlfphotonavigationcontrolstextdecoration.options[mnlfphotonavigationcontrolstextdecoration.selectedIndex].text;
	previewphotonavigationcontrols.style.color = mnlfphotonavigationcontrolsfontcolor.value;

	previewthumbnailsnavigationcontrols.style.fontFamily = mnlfthumbnailsnavigationcontrolsfontfamily.options[mnlfthumbnailsnavigationcontrolsfontfamily.selectedIndex].text;
	previewthumbnailsnavigationcontrols.style.fontSize = mnlfthumbnailsnavigationcontrolsfontsize.options[mnlfthumbnailsnavigationcontrolsfontsize.selectedIndex].text; 
	previewthumbnailsnavigationcontrols.style.fontWeight = mnlfthumbnailsnavigationcontrolsfontweight.options[mnlfthumbnailsnavigationcontrolsfontweight.selectedIndex].text;
	previewthumbnailsnavigationcontrols.style.fontStyle = mnlfthumbnailsnavigationcontrolsfontstyle.options[mnlfthumbnailsnavigationcontrolsfontstyle.selectedIndex].text;
	previewthumbnailsnavigationcontrols.style.textDecoration = mnlfthumbnailsnavigationcontrolstextdecoration.options[mnlfthumbnailsnavigationcontrolstextdecoration.selectedIndex].text;
	previewthumbnailsnavigationcontrols.style.color = mnlfthumbnailsnavigationcontrolsfontcolor.value;
	
	previewbody.style.backgroundColor = mnlfbodybgcolor.value;
	previewbody.style.fontFamily = mnlfbodyfontfamily.options[mnlfbodyfontfamily.selectedIndex].text;
	previewbody.style.fontSize = mnlfbodyfontsize.options[mnlfbodyfontsize.selectedIndex].text; 
	previewbody.style.fontWeight = mnlfbodyfontweight.options[mnlfbodyfontweight.selectedIndex].text;
	previewbody.style.fontStyle = mnlfbodyfontstyle.options[mnlfbodyfontstyle.selectedIndex].text;
	previewbody.style.textDecoration = mnlfbodytextdecoration.options[mnlfbodytextdecoration.selectedIndex].text;
	previewbody.style.textAlign = mnlfbodytextalign.value;
	previewbody.style.color = mnlfbodyfontcolor.value;

	previewtext.style.fontFamily = mnlfbodyfontfamily.options[mnlfbodyfontfamily.selectedIndex].text;
	previewtext.style.fontSize = mnlfbodyfontsize.options[mnlfbodyfontsize.selectedIndex].text; 
	previewtext.style.fontWeight = mnlfbodyfontweight.options[mnlfbodyfontweight.selectedIndex].text;
	previewtext.style.fontStyle = mnlfbodyfontstyle.options[mnlfbodyfontstyle.selectedIndex].text;
	previewtext.style.textDecoration = mnlfbodytextdecoration.options[mnlfbodytextdecoration.selectedIndex].text;
	previewtext.style.textAlign = mnlfbodytextalign.value;
	previewtext.style.color = mnlfbodyfontcolor.value;

	previewtdthumb.style.borderWidth = mnlftdthumbborderwidth.options[mnlftdthumbborderwidth.selectedIndex].text;
	previewtdthumb.style.borderStyle = mnlftdthumbborderstyle.options[mnlftdthumbborderstyle.selectedIndex].text;
	previewtdthumb.style.borderColor = mnlftdthumbbordercolor.value;

	previewtdthumbmouseover.style.borderWidth = mnlftdthumbborderwidth.options[mnlftdthumbborderwidth.selectedIndex].text;
	previewtdthumbmouseover.style.borderStyle = mnlftdthumbborderstyle.options[mnlftdthumbborderstyle.selectedIndex].text;
	previewtdthumbmouseover.style.borderColor = mnlftdthumbbordercolor.value;
	
	previewtdthumbselected.style.borderWidth = mnlftdthumbselectedborderwidth.options[mnlftdthumbselectedborderwidth.selectedIndex].text;
	previewtdthumbselected.style.borderStyle = mnlftdthumbselectedborderstyle.options[mnlftdthumbselectedborderstyle.selectedIndex].text;
	previewtdthumbselected.style.borderColor = mnlftdthumbselectedbordercolor.value;

	previewimgthumb.style.borderWidth = mnlfimgthumbborderwidth.options[mnlfimgthumbborderwidth.selectedIndex].text;
	previewimgthumb.style.borderStyle = mnlfimgthumbborderstyle.options[mnlfimgthumbborderstyle.selectedIndex].text;
	previewimgthumb.style.borderColor = mnlfimgthumbbordercolor.value;
	previewimgthumb.style.opacity = mnlfimgthumbopacity.value;

	previewimgthumbhover.style.borderWidth = mnlfimgthumbhoverborderwidth.options[mnlfimgthumbhoverborderwidth.selectedIndex].text;
	previewimgthumbhover.style.borderStyle = mnlfimgthumbhoverborderstyle.options[mnlfimgthumbhoverborderstyle.selectedIndex].text;
	previewimgthumbhover.style.borderColor = mnlfimgthumbhoverbordercolor.value;
	previewimgthumbhover.style.opacity = mnlfimgthumbhoveropacity.value;

	previewimgthumbselected.style.borderWidth = mnlfimgthumbselectedborderwidth.options[mnlfimgthumbselectedborderwidth.selectedIndex].text;
	previewimgthumbselected.style.borderStyle = mnlfimgthumbselectedborderstyle.options[mnlfimgthumbselectedborderstyle.selectedIndex].text;
	previewimgthumbselected.style.borderColor = mnlfimgthumbselectedbordercolor.value;
	previewimgthumbselected.style.opacity = mnlfimgthumbselectedopacity.value;

	previewdivphoto.style.borderWidth = mnlfdivphotoborderwidth.options[mnlfdivphotoborderwidth.selectedIndex].text;
	previewdivphoto.style.borderStyle = mnlfdivphotoborderstyle.options[mnlfdivphotoborderstyle.selectedIndex].text;
	previewdivphoto.style.borderColor = mnlfdivphotobordercolor.value;

	previewimgphoto.style.borderWidth = mnlfimgphotoborderwidth.options[mnlfimgphotoborderwidth.selectedIndex].text;
	previewimgphoto.style.borderStyle = mnlfimgphotoborderstyle.options[mnlfimgphotoborderstyle.selectedIndex].text;
	previewimgphoto.style.borderColor = mnlfimgphotobordercolor.value;

}

</script>

<center><br/>
	<table cellpadding="10" cellspacing="2" width="400">
		<tr>
			<td align="center"><p class="title"><? echo getResource("appearanceParams"); ?> :</p></td>
			<td  align="center">
				<p class="title"><? echo getResource("appearancePreview"); ?> :</p>
			</td>
		</tr>
		<tr>
			<td valign="top">
					<table class="normal">
						<tr>
							<td>
								<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
									<tr>
										<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsBody"); ?></td>
									</tr>
									<tr>
										<td>Background color : </td>
										<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfbodybgcolor\""; ?> name="mnlfbodybgcolor" id="mnlfbodybgcolor" onChange="javascript:UpdatePreview();"></td>	
									</tr>

									<tr>
										<td>Font family : </td>
										<td>
										<?
									getFontFamilyForm($mnlfbodyfontfamily,"mnlfbodyfontfamily","mnlfbodyfontfamily","UpdatePreview()");
									?>
								</td>
							</tr>
							<tr>
								<td>Font size : </td>
								<td>
								<?
							getFontSizeForm($mnlfbodyfontsize,"mnlfbodyfontsize","mnlfbodyfontsize","UpdatePreview()");
							?>
						</td>
					</tr>
					<tr>									
						<td>Font weight : </td>
						<td>
						<?
					getFontWeightForm($mnlfbodyfontweight,"mnlfbodyfontweight","mnlfbodyfontweight","UpdatePreview()");
					?>
				</td>
			</tr>
			<tr>									
				<td>Font style : </td>
				<td>
				<?
			getFontStyleForm($mnlfbodyfontstyle,"mnlfbodyfontstyle","mnlfbodyfontstyle","UpdatePreview()");
			?>
		</td>
	</tr>
	<tr>
		<td>Text decoration : </td>
		<td>
		<?
	getTextDecorationForm($mnlfbodytextdecoration,"mnlfbodytextdecoration","mnlfbodytextdecoration","UpdatePreview()");
	?>
</td>
</tr>
<tr>
	<td>Text align : </td>
	<td>
	<?
getTextAlignForm($mnlfbodytextalign,"mnlfbodytextalign","mnlfbodytextalign","UpdatePreview()");
?>

</td>
</tr>								
<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfbodyfontcolor\""; ?> name="mnlfbodyfontcolor" id="mnlfbodyfontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsLinks"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>

				<?
			getFontFamilyForm($mnlflinksfontfamily,"mnlflinksfontfamily","mnlflinksfontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlflinksfontsize,"mnlflinksfontsize","mnlflinksfontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
	<?
getFontWeightForm($mnlflinksfontweight,"mnlflinksfontweight","mnlflinksfontweight","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Font style : </td>
	<td>
	<?
getFontStyleForm($mnlflinksfontstyle,"mnlflinksfontstyle","mnlflinksfontstyle","UpdatePreview()");
?>
</td>
</tr>


<tr>
	<td>Text decoration : </td>
	<td>
	<?
getTextDecorationForm($mnlflinkstextdecoration,"mnlflinkstextdecoration","mnlflinkstextdecoration","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text align : </td>
	<td>
	<?
getTextAlignForm($mnlflinkstextalign,"mnlflinkstextalign","mnlflinkstextalign","UpdatePreview()");
?>

</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlflinksfontcolor\""; ?> name="mnlflinksfontcolor" id="mnlflinksfontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsLogo"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>

				<?
			getFontFamilyForm($mnlflogofontfamily,"mnlflogofontfamily","mnlflogofontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlflogofontsize,"mnlflogofontsize","mnlflogofontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
	<?
getFontWeightForm($mnlflogofontweight,"mnlflogofontweight","mnlflogofontweight","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Font style : </td>
	<td>
	<?
getFontStyleForm($mnlflogofontstyle,"mnlflogofontstyle","mnlflogofontstyle","UpdatePreview()");
?>
</td>
</tr>


<tr>
	<td>Text decoration : </td>
	<td>
	<?
getTextDecorationForm($mnlflogotextdecoration,"mnlflogotextdecoration","mnlflogotextdecoration","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text align : </td>
	<td>
	<?
getTextAlignForm($mnlflogotextalign,"mnlflogotextalign","mnlflogotextalign","UpdatePreview()");
?>

</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlflogofontcolor\""; ?> name="mnlflogofontcolor" id="mnlflogofontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsTitle"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>

				<?
			getFontFamilyForm($mnlftitlefontfamily,"mnlftitlefontfamily","mnlftitlefontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlftitlefontsize,"mnlftitlefontsize","mnlftitlefontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
	<?
getFontWeightForm($mnlftitlefontweight,"mnlftitlefontweight","mnlftitlefontweight","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Font style : </td>
	<td>
	<?
getFontStyleForm($mnlftitlefontstyle,"mnlftitlefontstyle","mnlftitlefontstyle","UpdatePreview()");
?>
</td>
</tr>


<tr>
	<td>Text decoration : </td>
	<td>
	<?
getTextDecorationForm($mnlftitletextdecoration,"mnlftitletextdecoration","mnlftitletextdecoration","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text align : </td>
	<td>
	<?
getTextAlignForm($mnlftitletextalign,"mnlftitletextalign","mnlftitletextalign","UpdatePreview()");
?>

</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlftitlefontcolor\""; ?> name="mnlftitlefontcolor" id="mnlftitlefontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsContact"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>

				<?
			getFontFamilyForm($mnlfcontactfontfamily,"mnlfcontactfontfamily","mnlfcontactfontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlfcontactfontsize,"mnlfcontactfontsize","mnlfcontactfontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
	<?
getFontWeightForm($mnlfcontactfontweight,"mnlfcontactfontweight","mnlfcontactfontweight","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Font style : </td>
	<td>
	<?
getFontStyleForm($mnlfcontactfontstyle,"mnlfcontactfontstyle","mnlfcontactfontstyle","UpdatePreview()");
?>
</td>
</tr>


<tr>
	<td>Text decoration : </td>
	<td>
	<?
getTextDecorationForm($mnlfcontacttextdecoration,"mnlfcontacttextdecoration","mnlfcontacttextdecoration","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text align : </td>
	<td>
	<?
getTextAlignForm($mnlfcontacttextalign,"mnlfcontacttextalign","mnlfcontacttextalign","UpdatePreview()");
?>

</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfcontactfontcolor\""; ?> name="mnlfcontactfontcolor" id="mnlfcontactfontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsCopyright"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>

				<?
			getFontFamilyForm($mnlfcopyrightfontfamily,"mnlfcopyrightfontfamily","mnlfcopyrightfontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlfcopyrightfontsize,"mnlfcopyrightfontsize","mnlfcopyrightfontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
	<?
getFontWeightForm($mnlfcopyrightfontweight,"mnlfcopyrightfontweight","mnlfcopyrightfontweight","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Font style : </td>
	<td>
	<?
getFontStyleForm($mnlfcopyrightfontstyle,"mnlfcopyrightfontstyle","mnlfcopyrightfontstyle","UpdatePreview()");
?>
</td>
</tr>


<tr>
	<td>Text decoration : </td>
	<td>
	<?
getTextDecorationForm($mnlfcopyrighttextdecoration,"mnlfcopyrighttextdecoration","mnlfcopyrighttextdecoration","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text align : </td>
	<td>
	<?
getTextAlignForm($mnlfcopyrighttextalign,"mnlfcopyrighttextalign","mnlfcopyrighttextalign","UpdatePreview()");
?>

</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfcopyrightfontcolor\""; ?> name="mnlfcopyrightfontcolor" id="mnlfcopyrightfontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>


<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsPhotoTitle"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>


				<?
			getFontFamilyForm($mnlfphototitlefontfamily,"mnlfphototitlefontfamily","mnlfphototitlefontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlfphototitlefontsize,"mnlfphototitlefontsize","mnlfphototitlefontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
	<?
getFontWeightForm($mnlfphototitlefontweight,"mnlfphototitlefontweight","mnlfphototitlefontweight","UpdatePreview()");
?>
</td>
</tr>

<tr>									
	<td>Font style : </td>
	<td>
	<?
getFontStyleForm($mnlfphototitlefontstyle,"mnlfphototitlefontstyle","mnlfphototitlefontstyle","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text decoration : </td>
	<td>
	<?
getTextDecorationForm($mnlfphototitletextdecoration,"mnlfphototitletextdecoration","mnlfphototitletextdecoration","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text align : </td>
	<td>
	<?
getTextAlignForm($mnlfphototitletextalign,"mnlfphototitletextalign","mnlfphototitletextalign","UpdatePreview()");
?>

</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfphototitlefontcolor\""; ?> name="mnlfphototitlefontcolor" id="mnlfphototitlefontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsPhotoDescription"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>


				<?
			getFontFamilyForm($mnlfphotodescriptionfontfamily,"mnlfphotodescriptionfontfamily","mnlfphotodescriptionfontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlfphotodescriptionfontsize,"mnlfphotodescriptionfontsize","mnlfphotodescriptionfontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
	<?
getFontWeightForm($mnlfphotodescriptionfontweight,"mnlfphotodescriptionfontweight","mnlfphotodescriptionfontweight","UpdatePreview()");
?>
</td>
</tr>

<tr>									
	<td>Font style : </td>
	<td>
	<?
getFontStyleForm($mnlfphotodescriptionfontstyle,"mnlfphotodescriptionfontstyle","mnlfphotodescriptionfontstyle","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text decoration : </td>
	<td>
	<?
getTextDecorationForm($mnlfphotodescriptiontextdecoration,"mnlfphotodescriptiontextdecoration","mnlfphotodescriptiontextdecoration","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Text align : </td>
	<td>
	<?
getTextAlignForm($mnlfphotodescriptiontextalign,"mnlfphotodescriptiontextalign","mnlfphotodescriptiontextalign","UpdatePreview()");
?>
</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfphotodescriptionfontcolor\""; ?> name="mnlfphotodescriptionfontcolor" id="mnlfphotodescriptionfontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsPhotoNavigationControls"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>


				<?
			getFontFamilyForm($mnlfphotonavigationcontrolsfontfamily,"mnlfphotonavigationcontrolsfontfamily","mnlfphotonavigationcontrolsfontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlfphotonavigationcontrolsfontsize,"mnlfphotonavigationcontrolsfontsize","mnlfphotonavigationcontrolsfontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
		<?
	getFontWeightForm($mnlfphotonavigationcontrolsfontweight,"mnlfphotonavigationcontrolsfontweight","mnlfphotonavigationcontrolsfontweight","UpdatePreview()");
	?>
</td>
</tr>

<tr>									
	<td>Font style : </td>
	<td>
		<?
	getFontStyleForm($mnlfphotonavigationcontrolsfontstyle,"mnlfphotonavigationcontrolsfontstyle","mnlfphotonavigationcontrolsfontstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Text decoration : </td>
	<td>
		<?
	getTextDecorationForm($mnlfphotonavigationcontrolstextdecoration,"mnlfphotonavigationcontrolstextdecoration","mnlfphotonavigationcontrolstextdecoration","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfphotonavigationcontrolsfontcolor\""; ?> name="mnlfphotonavigationcontrolsfontcolor" id="mnlfphotonavigationcontrolsfontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsThumbnailsNavigationControls"); ?></td>
			</tr>
			<tr>
				<td>Font family : </td>
				<td>


				<?
			getFontFamilyForm($mnlfthumbnailsnavigationcontrolsfontfamily,"mnlfthumbnailsnavigationcontrolsfontfamily","mnlfthumbnailsnavigationcontrolsfontfamily","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Font size : </td>
		<td>

		<?
	getFontSizeForm($mnlfthumbnailsnavigationcontrolsfontsize,"mnlfthumbnailsnavigationcontrolsfontsize","mnlfthumbnailsnavigationcontrolsfontsize","UpdatePreview()");
	?>

</td>
</tr>

<tr>
	<td>Font weight : </td>
	<td>
		<?
	getFontWeightForm($mnlfthumbnailsnavigationcontrolsfontweight,"mnlfthumbnailsnavigationcontrolsfontweight","mnlfthumbnailsnavigationcontrolsfontweight","UpdatePreview()");
	?>
</td>
</tr>

<tr>									
	<td>Font style : </td>
	<td>
		<?
	getFontStyleForm($mnlfthumbnailsnavigationcontrolsfontstyle,"mnlfthumbnailsnavigationcontrolsfontstyle","mnlfthumbnailsnavigationcontrolsfontstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Text decoration : </td>
	<td>
		<?
	getTextDecorationForm($mnlfthumbnailsnavigationcontrolstextdecoration,"mnlfthumbnailsnavigationcontrolstextdecoration","mnlfthumbnailsnavigationcontrolstextdecoration","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Font color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfthumbnailsnavigationcontrolsfontcolor\""; ?> name="mnlfthumbnailsnavigationcontrolsfontcolor" id="mnlfthumbnailsnavigationcontrolsfontcolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>


<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsPhotoThumbFrame"); ?></td>
			</tr>
			<tr>
				<td>Border width : </td>
				<td>
				<?
			getBorderWidthForm($mnlftdthumbborderwidth,"mnlftdthumbborderwidth","mnlftdthumbborderwidth","UpdatePreview()");
			?>

		</td>
	</tr>
	<tr>
		<td>Border style : </td>
		<td>
		<?
	getBorderStyleForm($mnlftdthumbborderstyle,"mnlftdthumbborderstyle","mnlftdthumbborderstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Border color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlftdthumbbordercolor\""; ?> name="mnlftdthumbbordercolor" id="mnlftdthumbbordercolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="1" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsSelectedPhotoThumbFrame"); ?></td>
			</tr>
			<tr>
				<td>Border width : </td>
				<td>
				<?
			getBorderWidthForm($mnlftdthumbselectedborderwidth,"mnlftdthumbselectedborderwidth","mnlftdthumbselectedborderwidth","UpdatePreview()");
			?>
		</td>
	</tr>
	<tr>
		<td>Border style : </td>
		<td>
		<?
	getBorderStyleForm($mnlftdthumbselectedborderstyle,"mnlftdthumbselectedborderstyle","mnlftdthumbselectedborderstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Border color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlftdthumbselectedbordercolor\""; ?> name="mnlftdthumbselectedbordercolor" id="mnlftdthumbselectedbordercolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsPhotoThumb"); ?></td>
			</tr>
			<tr>
				<td>Border width : </td>
				<td>
				<?
			getBorderWidthForm($mnlfimgthumbborderwidth,"mnlfimgthumbborderwidth","mnlfimgthumbborderwidth","UpdatePreview()");
			?>
		</td>
	</tr>
	<tr>
		<td>Border style : </td>
		<td>
		<?
	getBorderStyleForm($mnlfimgthumbborderstyle,"mnlfimgthumbborderstyle","mnlfimgthumbborderstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Border color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfimgthumbbordercolor\""; ?> name="mnlfimgthumbbordercolor" id="mnlfimgthumbbordercolor" onChange="javascript:UpdatePreview();"></td>
</tr>

<tr>
	<td>Thumb Opacity : </td>
	<td><input type="text" <? echo "value=\"$mnlfimgthumbopacity\""; ?> name="mnlfimgthumbopacity" id="mnlfimgthumbopacity" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsSelectedPhotoThumb"); ?></td>
			</tr>
			<tr>
				<td>Border width : </td>
				<td>
				<?
			getBorderWidthForm($mnlfimgthumbselectedborderwidth,"mnlfimgthumbselectedborderwidth","mnlfimgthumbselectedborderwidth","UpdatePreview()");
			?>
		</td>
	</tr>
	<tr>
		<td>Border style : </td>
		<td>
		<?
	getBorderStyleForm($mnlfimgthumbselectedborderstyle,"mnlfimgthumbselectedborderstyle","mnlfimgthumbselectedborderstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Border color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfimgthumbselectedbordercolor\""; ?> name="mnlfimgthumbselectedbordercolor" id="mnlfimgthumbselectedbordercolor" onChange="javascript:UpdatePreview();"></td>
</tr>

<tr>
	<td>Thumb Opacity : </td>                                                               
	<td><input type="text" <? echo "value=\"$mnlfimgthumbselectedopacity\""; ?> name="mnlfimgthumbselectedopacity" id="mnlfimgthumbselectedopacity" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsPhotoThumbMouseOver"); ?></td>
			</tr>
			<tr>
				<td>Border width : </td>
				<td>
				<?
			getBorderWidthForm($mnlfimgthumbhoverborderwidth,"mnlfimgthumbhoverborderwidth","mnlfimgthumbhoverborderwidth","UpdatePreview()");
			?>
		</td>
	</tr>
	<tr>
		<td>Border style : </td>
		<td>
		<?
	getBorderStyleForm($mnlfimgthumbhoverborderstyle,"mnlfimgthumbhoverborderstyle","mnlfimgthumbhoverborderstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Border color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfimgthumbhoverbordercolor\""; ?> name="mnlfimgthumbhoverbordercolor" id="mnlfimgthumbhoverbordercolor" onChange="javascript:UpdatePreview();"></td>
</tr>

<tr>
	<td>Thumb Opacity : </td>
	<td><input type="text" <? echo "value=\"$mnlfimgthumbhoveropacity\""; ?> name="mnlfimgthumbhoveropacity" id="mnlfimgthumbhoveropacity" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>


<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsSelectedPhoto"); ?></td>
			</tr>
			<tr>
				<td>Border width : </td>
				<td>
				<?
			getBorderWidthForm($mnlfimgphotoborderwidth,"mnlfimgphotoborderwidth","mnlfimgphotoborderwidth","UpdatePreview()");
			?>
		</td>
	</tr>
	<tr>
		<td>Border style : </td>
		<td>
		<?
	getBorderStyleForm($mnlfimgphotoborderstyle,"mnlfimgphotoborderstyle","mnlfimgphotoborderstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Border color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfimgphotobordercolor\""; ?> name="mnlfimgphotobordercolor" id="mnlfimgphotobordercolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

<tr>
	<td>
		<table class="normal" cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td colspan="2" align="center" class="header"><? echo getResource("appearanceParamsSelectedPhotoFrame"); ?></td>
			</tr>
			<tr>
				<td>Border width : </td>
				<td>
				<?
			getBorderWidthForm($mnlfdivphotoborderwidth,"mnlfdivphotoborderwidth","mnlfdivphotoborderwidth","UpdatePreview()");
			?>
		</td>
	</tr>
	<tr>
		<td>Border style : </td>
		<td>
		<?
	getBorderStyleForm($mnlfdivphotoborderstyle,"mnlfdivphotoborderstyle","mnlfdivphotoborderstyle","UpdatePreview()");
	?>
</td>
</tr>

<tr>
	<td>Border color : </td>
	<td><input <? echo "class=\"$color\""; ?> <? echo "value=\"$mnlfdivphotobordercolor\""; ?> name="mnlfdivphotobordercolor" id="mnlfdivphotobordercolor" onChange="javascript:UpdatePreview();"></td>
</tr>

</table>

</td>
</tr>

</table>


</td>

<td valign="top">

	<div id="previewbody" style="position:fixed;">
		<div class="body" id="previewtext"><? echo getResource("appearanceBodyText"); ?></div><br />
		<div class="logo" id="previewlogo"><? echo getResource("appearanceLogo"); ?></div><br />
		<div class="title" id="previewtitle"><? echo getResource("appearanceTitle"); ?></div><br />
		<a id="previewlinks" href="#"><? echo getResource("appearanceLinks"); ?></a><br/><br/>
		<a id="previewcontact" href="#"><? echo getResource("appearanceContact"); ?></a>
		<table border="0">
			<tr>
				<td valign="top">
					<table cellspacing="4" cellpadding="0" width="100%">
						<tr style="font-family:'Trebuchet MS';font-size:10px;">
							<td align="left">
								<? echo getResource("appearanceParamsPhotoThumb"); ?> |
								<? echo getResource("appearanceParamsSelectedPhotoThumb"); ?> |
								<? echo getResource("appearanceParamsPhotoThumbMouseOver"); ?> :
						</tr>
					</table>
					<table cellspacing="4" cellpadding="0">
						<tr>
							<td class="thumb" id="previewtdthumb">
								<img class="thumb" id="previewimgthumb" src="design/images/thumb.jpg" />
							</td>
							<td class="thumbselected" id="previewtdthumbselected">
								<img class="thumbselected" id="previewimgthumbselected" src="design/images/thumb.jpg" />
							</td>
							<td class="thumb" id="previewtdthumbmouseover">
								<img id="previewimgthumbhover" src="design/images/thumb.jpg" />
							</td>
						</tr>
					</table>
					<table cellspacing="0" cellpadding="0">
					 <tr>
					   <td class="thumbnailsNavigationControls" id="previewthumbnailsnavigationcontrols">
					   	<? echo getTypedConf("NextThumbnailsPageControlLabel"); ?>
					   </td>
					 </tr>					
					</table>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td><br />
								<div class="photo" id="previewdivphoto">
									<img class="photo" id="previewimgphoto" src="design/images/photo.jpg" />
								</div>
							</td>
						</tr>
						<tr>
						   <td>
								<table cellspacing="0" cellpadding="1" style="width:100%">
									<tr>
										<td width="15%">
										</td>						
										<td class="photoTitle" id="previewphototitle" width="70%">
											<center><? echo getResource("appearancePhotoTitle"); ?></center>
										</td>
										<td class="photoNavigationControls" id="previewphotonavigationcontrols" width="15%">
											<? echo getTypedConf("NextPhotoControlLabel"); ?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="photoDescription" id="previewphotodescription" >
								Lorem ipsum dolor sit amet, consectetur...
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table> 
		<p id="previewcopyright"><? echo getResource("appearanceCopyright"); ?></p>
	<div align="center"><p class="importantButton"><input type="submit" <? echo "value=\">>>> ".getResource("btnSaveChanges")." <<<<\""; ?> ></p></div>
	</div>
</td>
</tr>
</table>
</center>








