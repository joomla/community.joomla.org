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
$percent =  100 - DonationHelper::getConfigValue('percent_commission') ;
?>
<hr />
<form action="<?php echo JRoute::_('index.php?option=com_jdonation&view=donors&Itemid='.$this->Itemid); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th>
					Full Name Organisation/Company
				</th>
				<th>
					<?php echo JText::_('JD_DONATION_DATE') ; ?>
				</th>
				<th>
					<?php echo JText::_('JD_DONATION_AMOUNT') ; ?>
				</th>
				<th>
				Reason for sponsoring</th>
			</tr>
		</thead>		
		<tbody>
		<?php						
			for ($i = 0 , $n = count($this->items) ; $i < $n ; $i++)
            {
				$row = $this->items[$i] ;
				if ($row->hide_me == 1)
                {
					$row->last_name = JText::_('JD_ANONYMOUS');
					$row->organization = '';
					$row->comment = '';
				}								
			?>
				<tr>
					<td>
					<?php echo $row->last_name ; ?> <em><?php echo $row->organization; ?></em>
						
					</td>
					<td>
						<?php echo  JHtml::_('date', $row->created_date, $this->config->date_format, null); ?>
					</td>
					<td>
						<?php echo DonationHelperHtml::formatAmount($this->config, $row->amount); ?>
					</td>
					<td>
					<?php echo $row->comment ; ?>
					</td>
				</tr>
			<?php	
			}
			if ($this->pagination->total > $this->pagination->limit)
            {
			?>
				<tr>
					<td colspan="5" align="center">
						<div class="pagination"><?php echo $this->pagination->getListFooter(); ?></div>
					</td>
				</tr>
			<?php	
			}
		?>			
		</tbody>		
	</table>	
</form>