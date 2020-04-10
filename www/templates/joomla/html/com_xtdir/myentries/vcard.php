<?php

/**
 * @package     Extly.Components
 * @subpackage  com_xtdir - Extended Directory for SobiPro
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2016 Prieco, S.A. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

$header_title = JText::_('COM_XTDIR_VIEW_MYENTRIES_HEADER_TITLE');
$header_cat = JText::_('COM_XTDIR_VIEW_MYENTRIES_HEADER_CATEGORY');
$header_date = JText::_('COM_XTDIR_VIEW_MYENTRIES_HEADER_DATE_ADDED');

$items = $this->get('items');

if ($this->get('show_addentry_link'))
{
	$catalog_list = $this->get('catalog_list');
	$section_id = $catalog_list[0];

	$sef = (!F0FPlatform::getInstance()->isBackend());
	$params = array(
					'sid' => $section_id,
					'task' => 'entry.add'
	);
	$url = Sobi::Url($params, false, $sef, false, false);

	echo '<p class="add-link text-right"><a href="' . $url . '">';
	echo JText::_('COM_XTDIR_MOD_ADDENTRYLINK');
	echo '</a><p>';
}

if (empty($items))
{
	echo '<p>' . JText::_('COM_XTDIR_VIEW_MYENTRIES_NO_ENTRIES') . '</p>';

	return true;
}

$data = $this->_preProcess($items);
$menu_itemid = $this->get('menu_itemid');
$truncate_descs = EParameter::getComponentParam(CXTDIR, 'truncate_descs', UtilHelper::DESCS_TEXT_LENGHT);
$user_mode = $this->get('user_mode');

// EasySocial
if (($user_mode == 2) && (!$this->get('is_module')))
{
	echo $this->esWidgetHeader();
}

?>

<div class="row-fluid entry-container <?php echo $this->get('style'); ?>">
	<div class="span12">
<?php

	$html = array();
	$html[] = "<tbody>";
	$i = 0;
	$sid = JFactory::getApplication()->input->get('sid', 0, 'int');

	foreach ($data as $id => $item)
	{
		if ($sid == $id)
		{
			continue;
		}

		$rowclass = ($i % 2) ? "row-even" : "row-odd";

		if (isset($items[$i]->promotype_id))
		{
			$rowclass .= ' xtd-vcard promoted_' . $items[$i]->promotype_id;
			$rowclass .= ' promoted_' . $items[$i]->tiertype_id;
			$rowclass .= ' promoted_' . $items[$i]->fordering;
		}


		$date = JFactory::getDate($item['publish_up']);
		$postDate = $date->format(JText::_('DATE_FORMAT_LC4'));

		$entry = $item['entry'];
		$categories = $entry->getCategories();

		foreach ($categories as $key => $category)
		{
			$pid = $category['pid'];
			break;
		}

		$alias = (Sobi::Cfg('sef.alias', true) ? $item['nid'] : $item['title']);
		$itemlink = SobiproHelper::getUrl(
			'entry',
			$id,
			$pid,
			$alias,
			$menu_itemid
		);

		$itemtitle = $item['title'];
		$cats = $item['cats'];

?>
			<div class="row-fluid <?php echo $rowclass; ?>">
				<div class="span12 thumbnail">
					<span class="lead">
						<a href="<?php echo $itemlink; ?>">
							<?php echo $itemtitle; ?>
						</a>
					</span>
					<?php
						$imagefield_id = $this->get('imagefield_id');
						$descfield_id = $this->get('descfield_id');

						if (($imagefield_id) || ($descfield_id))
						{
					?>
					<p class="text-info">
						<?php
								if ($imagefield_id)
								{
									if (is_array($imagefield_id))
									{
										foreach ($imagefield_id as $img_id)
										{
											$this->showImage($entry, $img_id);
										}
									}
									else
									{
										$this->showImage($entry, $imagefield_id);
									}
								}

								if ($descfield_id)
								{
									if (is_array($descfield_id))
									{
										foreach ($descfield_id as $dsc_id)
										{
											$this->showDesc($entry, $dsc_id, $truncate_descs);
										}
									}
									else
									{
										$this->showDesc($entry, $descfield_id, $truncate_descs);
									}
								}
						?>
					</p>
					<?php
						}
					?>
				</div>
			</div>
<?php
	}
?>
	</div>
</div>
<?php

// EasySocial
if (($user_mode == 2) && (!$this->get('is_module')))
{
	echo $this->esWidgetFooter();
}
