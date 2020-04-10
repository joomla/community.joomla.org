<?php
/**
 * @version        4.3
 * @package        Joomla
 * @subpackage     Joom Donation
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2009 - 2016 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;
if ($this->config->use_https)
{
	$ssl = 1;
}
else 
{
	$ssl = 0;
}
?>
<div id="donation-campaigns" class="row-fluid jd-container">
	<!-- Campaigns List -->
	<?php if(count($this->items))		 
	{
	?>	    
	<div>
	{module module="324" showtitle="true"}</div>
	<?php	        
		for ($i = 0 , $n = count($this->items) ;  $i < $n ; $i++) 
		{	        	
			$item = $this->items[$i];
			$donatedPercent = ceil($item->total_donated/ $item->goal *100);
			$url = JRoute::_(DonationHelperRoute::getDonationFormRoute($item->id, $this->Itemid), false, $ssl);
		?>
			<div class="jd-row clearfix">
				<div class="jd-box-heading clearfix">
					<h3 class="jd_title">																										
						<a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>">
							<?php echo $item->title; ?>
						</a>
					</h3>
				</div>
				<div class="jd-description">
					<div class="row-fluid">
						<div class="osm-description-details span12">
							<?php
							if(($item->campaign_photo != "") && (JPATH_ROOT.'/images/jdonation/'.$item->campaign_photo)){
								?>
								<div class="jd-description-photo">
									<img src="<?php echo JUri::root()?>images/jdonation/<?php echo $item->campaign_photo?>" class="img img-polaroid" />
								</div>
								<?php
							}
							?>
							<?php echo $item->description ; ?>
						</div>
					</div>
				</div>
				<!-- DONATE BUTTON -->
				<div class="row-fluid clearfix">
					<?php
					if ($this->config->show_campaign_progress !== '0' && $item->goal > 0)
					{
					?>
						<div class="donate-details clearfix">
							<div class="span10">
								<div class="row-fluid">
									<div class="span6">
										<strong class="orange">
											<?php echo DonationHelperHtml::formatAmount($this->config, $item->total_donated); ?>
										</strong>
										<span><?php echo JText::_('JD_RAISED'); ?></span>
										<?php echo ' / '; ?>
										<strong class="darkgray">
											<?php echo DonationHelperHtml::formatAmount($this->config, $item->goal) ; ?>
										</strong>
										<span><?php echo JText::_('JD_GOAL'); ?></span>
									</div>
									<div class="span2">
										<strong class="darkgray"><?php echo $donatedPercent; ?>% </strong><span><?php echo JText::_('JD_DONATED'); ?></span>
									</div>
									<div class="span2">
										<strong class="darkgray"><?php echo (int)$item->number_donors; ?> </strong><span><?php echo JText::_('JD_DONORS'); ?></span>
									</div>
									<div class="span2">
										<?php
										if ($item->days_left > 0)
										{
											?>
											<strong class="darkgray"><?php echo $item->days_left; ?> </strong><span><?php echo JText::_('JD_DAYS_LEFT'); ?></span>
										<?php
										}
										?>
									</div>
								</div>

								<div class="progress">
									<div class="bar" style="width: <?php echo $donatedPercent; ?>%"></div>
								</div>
							</div>
							<?php
							if((($item->end_date != "") or ($item->end_date != "0000-00-00 00:00:00")) && (strtotime($item->end_date) > time())){
							?>
							<div class="jd-taskbar span2">
								<a class="btn btn-primary" href="<?php echo $url; ?>">
									<?php echo JText::_('JD_DONATE_NOW'); ?>
								</a>
							</div>
							<?php } elseif(($item->end_date == "") or ($item->end_date == "0000-00-00 00:00:00")){
								?>
								<div class="jd-taskbar span2">
									<a class="btn btn-primary" href="<?php echo $url; ?>">
										<?php echo JText::_('JD_DONATE_NOW'); ?>
									</a>
								</div>
								<?php
							}?>
						</div>
					<?php
					}
					else
					{
						if((($item->end_date != "") or ($item->end_date != "0000-00-00 00:00:00")) && (strtotime($item->end_date) > time())){
							?>
							<div class="donate-details clearfix">
								<div class="jd-taskbar" style="float:right;">
									<a class="btn btn-primary" href="<?php echo $url; ?>">
										<?php echo JText::_('JD_DONATE_NOW'); ?>
									</a>
								</div>
							</div>
							<?php
							}elseif(($item->end_date == "") or ($item->end_date == "0000-00-00 00:00:00")) {
							?>
							<div class="donate-details clearfix">
								<div class="jd-taskbar" style="float:right;">
									<a class="btn btn-primary" href="<?php echo $url; ?>">
										<?php echo JText::_('JD_DONATE_NOW'); ?>
									</a>
								</div>
							</div>
							<?php
						}
					}
					?>
				<!-- END DONATE BUTTON -->
				</div>
			</div>
		<?php	
		}
		if ($this->pagination->total > $this->pagination->limit) 
		{
		?>
			<div class="pagination">
				<?php echo $this->pagination->getListFooter(); ?>
			</div>
		<?php	
		}    	 
	}
?>		
</div>