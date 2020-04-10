<?php
/**
 * @package		Joomla.Site
 * @subpackage	Responsive Menu
 * @copyright	Copyright (C) Cecil Gupta. All rights reserved.
 * @license		GNU General Public License version 2 or later;
 */

// No direct access.
defined('_JEXEC') or die;
if(version_compare(JVERSION,'1.6.0','ge')) {
	// JOOMLA 1.6+ CODE
	$document =JFactory::getDocument();
	$document->addStyleSheet("media/mod_responsivemenu/css/theme4.css.php?maxMobileWidth=".$maxMobileWidth."&amp;menuBG=".$menuBG."&amp;textColor=".$textColor."&amp;textColor2=".$textColor2);
	if($rtlLayout){
		$document->addStyleSheet("media/mod_responsivemenu/css/theme4-rtl.css.php?maxMobileWidth=".$maxMobileWidth."&amp;menuBG=".$menuBG."&amp;textColor=".$textColor);
	}
	$document->addScript("modules/mod_responsivemenu/js/jquery.easing.1.3.js",'text/javascript', true);
	$document->addScript("templates/joomla/html/mod_responsivemenu/js/theme4.js",'text/javascript', true);
	?>
	<div id="responsiveMenuTheme4Cnt" style="float: left;">
	<a class="responsiveMenu4 toggleMenu <?php echo ($rtlLayout) ? 'rtlLayout' : ''; ?>" href="#"><?php echo (JText::_('MOD_RESPONSIVEMENU_MENU') != "") ? '<span>'.JText::_('MOD_RESPONSIVEMENU_MENU').'</span>' : "";?></a>
	<ul id="responsiveMenu<?php echo $module->id; ?>" class="responsiveMenuTheme4  <?php echo $class_sfx;?> <?php echo ($rtlLayout) ? 'rtlLayout' : ''; ?>"<?php
		$tag = '';
		if ($params->get('tag_id')!=NULL) {
			$tag = $params->get('tag_id').'';
			echo ' id="'.$tag.'"';
		}
	?>><input type="hidden" name="maxMobileWidth" value="<?php echo $maxMobileWidth; ?>" class="maxMobileWidth"/>
	<?php
	foreach ($list as $i => &$item) :
		$class = '';
		if ($item->id == $active_id) {
			$class .= 'current ';
		}
		
		if($i==0){
			$class .= 'first ';
		}

		
		if (	$item->type == 'alias' &&
				in_array($item->params->get('aliasoptions'),$path)
			||	in_array($item->id, $path)) {
		  $class .= 'active ';
		}
		if ($item->deeper) {
			$class .= 'deeper ';
		}
		
		if ($item->parent) {
			$class .= 'parent ';
		}

		if (!empty($class)) {
			$class = ' class="'.trim($class) .'"';
		}

		echo '<li id="item-'.$item->id.'"'.$class.'>';

		// Render the menu item.
		switch ($item->type) :
			case 'separator':
			case 'url':
			case 'heading':
			case 'component':
				require JModuleHelper::getLayoutPath('mod_responsivemenu', 'default_'.$item->type);
				break;

			default:
				require JModuleHelper::getLayoutPath('mod_responsivemenu', 'default_url');
				break;
		endswitch;

		// The next item is deeper.
		if ($item->deeper) {
			echo '<ul>';
		}
		// The next item is shallower.
		else if ($item->shallower) {
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		// The next item is on the same level.
		else {
			echo '</li>';
		}
	endforeach;
	?></ul>
	</div>
	<?php 
}