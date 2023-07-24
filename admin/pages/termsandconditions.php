<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.4 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

*****************************************************************************/
$mainTitle=Message('termsandconditions_navlabel');if(!$admin['r_admin']&&!$admin['r_demo']&&!$admin[$page['permissionrole']])throw new Exception(Message('error_access_denied'));$selectedLang=(isset($_POST['lang']))?$_POST['lang']:$i18n->getCurrentLanguage();
$termsFile = BASE_FOLDER.'/admin/config/termsandconditions.xml';if(!file_exists($termsFile))throw new Exception('File does not exist: '.$termsFile);$xml=simplexml_load_file($termsFile);$termsConfig=$xml->xpath(escape("//pagecontent[@lang='".$selectedLang."'][1]"));
if(!$termsConfig)throw new Exception('No terms and conditions available for this language. Create manually a new entry at '.$termsFile);if(!$show){?><h1><?php echo$mainTitle;?></h1><p><?php echo Message('termsandconditions_introduction');?></p>
<form action='<?php echo escapeOutput($_SERVER['PHP_SELF']);?>'method='post'class='form-inline'><input type='hidden'name='site'value='<?php echo$site;?>'><label for='lang'><?php echo Message('termsandconditions_label_language');?></label> <select name='lang'id='lang'>
<?php foreach($i18n->getSupportedLanguages() as$language){echo"<option value=\"$language\"";if($language==$selectedLang)echo' selected';echo">$language</option>";}?></select><button type='submit'class='btn'><?php echo Message('button_display');?></button></form>
<form action='<?php echo escapeOutput($_SERVER['PHP_SELF']);?>'method='post'class='form-horizontal'><input type='hidden'name='show'value='save'><input type='hidden'name='lang'value='<?php echo escapeOutput($selectedLang);?>'>
<input type='hidden'name='site'value='<?php echo$site;?>'><fieldset><?php $formFields=[];$terms=(string)$termsConfig[0];$formFields['content']=array('type'=>'html','value'=>$terms,'required'=>'true');foreach($formFields as$fieldId=>$fieldInfo)
echo FormBuilder::createFormGroup($i18n,$fieldId,$fieldInfo,$fieldInfo['value'],'imprint_label_');?></fieldset><div class='form-actions'><input type='submit'class='btn btn-primary'accesskey='s'title='Alt + s'value='<?php echo Message('button_save');?>'>
<input type='reset'class='btn'value='<?php echo Message('button_reset');?>'></div></form><?php }elseif($show=='save'){if(!isset($_POST['content'])||!strlen($_POST['content']))$err[]=Message('imprint_validationerror_content');
if(!is_writable($termsFile))$err[]=Message('termsandconditions_err_filenotwritable',$termsFile);if($admin['r_demo'])$err[]=Message('validationerror_no_changes_as_demo');if(isset($err))include('validationerror.inc.php');else{
echo'<h1>'.$mainTitle.' &raquo; '.Message('subpage_save_title').'</h1>';$termsContent=stripslashes($_POST['content']);$node=dom_import_simplexml($termsConfig[0]);$no=$node->ownerDocument;foreach($node->childNodes as$child)if($child->nodeType==XML_CDATA_SECTION_NODE)
$node->removeChild($child);$node->appendChild($no->createCDATASection($termsContent));$xml->asXML($termsFile);echo createSuccessMessage(Message('alert_save_success'),'');echo'<p>&raquo; <a href=\'?site='.$site.'\'>'.Message('back_label').'</a></p>';}}