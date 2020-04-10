<?php
/**
 * @version        4.3
 * @package        Joomla
 * @subpackage     Joom Donation
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2009 - 2016 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;
?>
<div id="donation-history-page" class="row-fluid jd-container">
<h1 class="jd-title"><?php echo JText::_('JD_DONATION_HISTORY') ; ?></h1>
<form action="<?php echo JRoute::_('index.php?option=com_jdonation&Itemid='.$this->Itemid); ?>" method="post" name="adminForm" id="adminForm">	
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th width="5%" class="hidden-phone">
					<?php echo JText::_('JD_NO'); ?>	
				</th>			
				<th>
					<?php echo JText::_('JD_CAMPAIGN') ?>
				</th>
				<th class="center">
					<?php echo JText::_('JD_DONATION_DATE') ; ?>
				</th>
				<th class="center">
					<?php echo JText::_('JD_DONATION_AMOUNT') ; ?>
				</th>
				<th class="hidden-phone">
					Transaction Information
</th>
			</tr>
		</thead>				
		<tbody>		
		<?php						
			for ($i = 0 , $n = count($this->items) ; $i < $n ; $i++) {
				$row = $this->items[$i] ;												
			?>
				<tr>
					<td class="hidden-phone">
						<?php echo $i + 1 ; ?>
					</td>
					<td>
						<?php echo $row->title; ?>
					</td>				
					<td class="center">
						<?php echo JHtml::_('date', $row->payment_date, $this->config->date_format) ; ?>
					</td>
					<td align="right">
						<?php echo DonationHelperHtml::formatAmount($this->config, $row->amount); ?>
					</td>					
					<td class="hidden-phone">
					<?php 
						if ($this->config->activate_donation_receipt_feature  && $row->published == '1') {
						?>
						<?php echo $row->transaction_id ; ?>
					
							<br />
								<a href="<?php echo JRoute::_('index.php?option=com_jdonation&task=download_receipt&id='.$row->id); ?>" title="<?php echo JText::_('JD_DOWNLOAD'); ?>"><?php echo JText::_('JD_DOWNLOAD'); ?> Receipt</a>
							
						<?php	
						} else {?>
						
						Transaction pending
						<?php }	
					?></td>
				</tr>
			<?php	
			}
			if ($this->pagination->total > $this->pagination->limit) {
				if ($this->config->activate_donation_receipt_feature)
					$cols = 6 ;
				else
					$cols = 5 ;
			?>
				<tr>
					<td colspan="<?php echo $cols; ?>">
						<div class="pagination"><?php echo $this->pagination->getListFooter(); ?></div>
					</td>
				</tr>
			<?php	
			}
		?>				
		</tbody>	
	</table>
</form>
</div>