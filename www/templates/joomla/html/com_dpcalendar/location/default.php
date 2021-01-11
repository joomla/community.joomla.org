<?php
/**
 * @package   DPCalendar
 * @copyright Copyright (C) 2016 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die();

if ($this->params->get('location_show_map', 1) && $this->params->get('map_provider', 'openstreetmap') != 'none') {
	$this->layoutHelper->renderLayout('block.map', $this->displayData);
}

$this->dpdocument->loadStyleFile('dpcalendar/views/location/default.css');
$this->dpdocument->loadScriptFile('dpcalendar/views/location/default.js');
$this->dpdocument->addStyle($this->params->get('location_custom_css'));
?>
<div class="com-dpcalendar-location<?php echo $this->pageclass_sfx ? ' com-dpcalendar-location-' . $this->pageclass_sfx : ''; ?>">
	<?php echo $this->layoutHelper->renderLayout('block.timezone', $this->displayData); ?>
	<?php echo $this->loadTemplate('heading'); ?>
	<?php echo $this->loadTemplate('title'); ?>
	<?php echo $this->loadTemplate('header'); ?>
	<div class="com-dpcalendar-location__loader">
		<?php echo $this->layoutHelper->renderLayout('block.loader', $this->displayData); ?>
	</div>
	<?php echo $this->loadTemplate('resource'); ?>
  	<div class="row-fluid">
  		<div class="span6">
	<?php echo $this->loadTemplate('map'); ?>
  		</div>
  		<div class="span6">
	<?php echo $this->loadTemplate('details'); ?>
            <?php echo trim(implode(
                      "\n",
                      $this->app->triggerEvent('onContentBeforeDisplay', ['com_dpcalendar.location', &$this->location, &$params, 0])
                  )); ?>
  		</div>
    </div>
    <div class="row-fluid">
    <div class="span9">
  		<?php echo JHTML::_('content.prepare', $this->location->description); ?>
    </div>
    <div class="span3">
	        <?php echo trim(implode(
		        "\n",
		        $this->app->triggerEvent('onContentAfterDisplay', ['com_dpcalendar.location', &$this->location, &$params, 0])
	        )); ?>
        </div>
    </div>

	<?php echo $this->loadTemplate('tags'); ?>
  	<?php echo $this->loadTemplate('icons'); ?>
	<?php echo $this->loadTemplate('events'); ?>
</div>
