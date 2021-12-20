<?php
/**
 * @package	RSDirectory!
 * @copyright	(c) 2013 - 2019 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');

$params = $this->params;
$options = $this->options;

?>

<div class="rsdir">
		<div class="<?php echo htmlspecialchars($this->pageclass_sfx); ?>">

			<?php if ( $params->get('show_page_heading', 1) ) { ?>
				<div class="page-header">
					<h1><?php echo $this->escape( $params->get('page_heading') ); ?></h1>
				</div>
			<?php } ?>
            
			<div class="row-fluid">
				<div class="span4">

                    <form id="rsdir-radius-search" action="<?php echo htmlspecialchars( JUri::getInstance()->toString() ); ?>" method="post">
        
                        <fieldset>
        
                            <legend><?php echo JText::_('COM_RSDIRECTORY_LOCATION'); ?></legend>
        
                            <div class="control-group">
                                <label class="control-label" for="rsdir-location"><?php echo JText::_('COM_RSDIRECTORY_LOCATION'); ?></label>
                                <input id="rsdir-location" class="input-xxlarge" type="text" name="location" value="<?php echo $this->escape($this->location);?>" autocomplete="off" placeholder="<?php echo JText::_('COM_RSDIRECTORY_LOCATION'); ?>" />
                            </div>
        
                            <label class="control-label" for="rsdir-radius"><?php echo JText::_('COM_RSDIRECTORY_RADIUS'); ?></label>
        
                            <div class="control-group clearfix">
        
                                <input id="rsdir-radius" class="input-mini" type="text" name="radius" value="<?php echo $this->escape($this->radius);?>" placeholder="<?php echo JText::_('COM_RSDIRECTORY_RADIUS'); ?>" />
                                <select id="rsdir-unit" class="input-mini" name="unit">
                                    <option value="km"<?php echo ($this->escape($this->unit) == 'km') ? ' selected' : ''; ?>><?php echo JText::_('COM_RSDIRECTORY_KM'); ?></option>
                                    <option value="mi"<?php echo ($this->escape($this->unit) == 'mi') ? ' selected' : ''; ?>><?php echo JText::_('COM_RSDIRECTORY_MI'); ?></option>
                                </select>
        
                            </div>
        
                        </fieldset>
        
                        <?php if ( !empty($this->fields) ) { ?>
                            <fieldset>
        
                                <legend><?php echo JText::_('COM_RSDIRECTORY_FILTERS'); ?></legend>
        
                                <?php
                                foreach ($this->fields as $field)
                                {
                                    echo RSDirectoryFilter::getInstance($field, $options)->generate();
                                }
                                ?>
                            </fieldset>
                        <?php } ?>
        
                        <div class="control-group">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i> <?php echo JText::_('COM_RSDIRECTORY_SEARCH'); ?>
                            </button>
                            <img class="loader hide" src="<?php echo JUri::root(true); ?>/media/com_rsdirectory/images/loader.gif" width="16" height="16" alt="" />
                            <input type="hidden" name="option" value="com_rsdirectory" />
                            <input type="hidden" name="task" value="radius.getDataAjax" />
                            <?php echo JHtml::_('form.token'); ?>
                            <?php if ($options) { ?>
                                <?php foreach ($options as $name => $value) { ?>
                                    <input class="options" type="hidden" name="<?php echo RSDirectoryHelper::escapeHTML($name); ?>" value="<?php echo RSDirectoryHelper::escapeHTML($value); ?>" />
                                <?php } ?>
                            <?php } ?>
                        </div>
        
                    </form>
				</div>

				<div class="span8">
			        <div id="rsdir-map-canvas" class="rsdir-map" style="width: <?php echo $this->width; ?>; height: <?php echo $params->get('height', 500); ?>px;"></div>
				</div>
			</div>

			<?php if ($this->params->get('display_results', 1)) { ?>
				<table class="table table-striped" style="display: none;">
					<thead>
					</thead>
					<tbody id="rsdir-map-results">
					</tbody>
				</table>
			<?php } ?>
		</div>
</div><!-- .rsdir -->

<script type="text/javascript">
    var rsdir_use_geolocation = <?php echo $this->use_geolocation;?>;
</script>