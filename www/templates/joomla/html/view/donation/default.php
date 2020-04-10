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
if ($this->config->use_https)
{
	$url = JRoute::_('index.php?option=com_jdonation&Itemid='.$this->Itemid, false, 1);
}
else
{
	$url = JRoute::_('index.php?option=com_jdonation&Itemid='.$this->Itemid, false);
}
DonationHelperJquery::validateForm();
//Validation rule fo custom amount
$amountValidationRules = '';
$minDonationAmount = (int) $this->config->minimum_donation_amount;
$maxDonationAmount = (int) $this->config->maximum_donation_amount;
if ($minDonationAmount)
{
	$amountValidationRules .= ",min[$minDonationAmount]";
}
if ($maxDonationAmount)
{
	$amountValidationRules .= ",max[$maxDonationAmount]";
}
$selectedState = '';
?>
<script type="text/javascript">
	<?php echo $this->recurringString ;?>
	var siteUrl	= "<?php echo DonationHelper::getSiteUrl(); ?>";
</script>
<script type="text/javascript" src="<?php echo DonationHelper::getSiteUrl().'media/com_jdonation/assets/js/jdonation.js'?>"></script>
<script type="text/javascript" src="<?php echo DonationHelper::getSiteUrl().'media/com_jdonation/assets/js/fblike.js'?>"></script>
<div id="donation-form" class="row-fluid jd-container">
<h1 class="jd-page-title"><?php echo $this->campaign->title; ?></h1>
<?php
//show campaign
if($this->campaign->id > 0){
	$campaign_link = JUri::getInstance()->toString(array('scheme', 'user', 'pass', 'host')).JRoute::_(DonationHelperRoute::getDonationFormRoute($this->campaign->id,JRequest::getInt('Itemid',0)));
	?>
	<div class="row-fluid">
		<div class="span12">
			<?php 
			$config=JFactory::getConfig();
			if(JVERSION>=3.0)
				$site_name=$config->get( 'sitename' );
			else
				$site_name=$config->getvalue( 'config.sitename' );
			
			require_once(JPATH_SITE . "/components/com_jdonation/helper/integrations.php");
			$doc = JFactory::getDocument();
			$doc->addCustomTag( '<meta property="og:title" content="'.$this->campaign->title.'" />' );
			if(($this->campaign->campaign_photo != "") && (file_exists(JPATH_ROOT.'/images/jdonation/'.$this->campaign->campaign_photo))){
				$doc->addCustomTag( '<meta property="og:image" content="'.JUri::root().'images/jdonation/'.$this->campaign->campaign_photo.'" />' );
			}
			$doc->addCustomTag( '<meta property="og:url" content="'.$campaign_link.'" />' );
			$doc->addCustomTag( '<meta property="og:description" content="'.nl2br(strip_tags(addslashes($this->campaign->description))).'" />' );
			$doc->addCustomTag( '<meta property="og:site_name" content="'.$site_name.'" />' );
			$doc->addCustomTag( '<meta property="og:type" content="article" />' );
			if($this->config->social_sharing == 1)
			{
				if($this->config->social_sharing_type == 1)
				{
					$add_this_share='
					<!-- AddThis Button BEGIN -->
					<div class="addthis_toolbox addthis_default_style">
					<a class="addthis_button_facebook_like" fb:like:layout="button_count" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
					<a class="addthis_button_google_plusone" g:plusone:size="medium" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
					<a class="addthis_button_tweet" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
					<a class="addthis_button_pinterest_pinit" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
					<a class="addthis_counter addthis_pill_style" class="addthis_button" addthis:url="'.$campaign_link.'"></a>
					</div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid="'.$this->config->addthis_publisher.'"></script>
					<!-- AddThis Button END -->' ;
					$add_this_js='https://s7.addthis.com/js/300/addthis_widget.js';
					$JdonationIntegrationsHelper=new JdontionIntegrationsHelper();
					$JdonationIntegrationsHelper->loadScriptOnce($add_this_js);
					//output all social sharing buttons
					echo' <div id="rr" style="">
						<div class="social_share_container">
						<div class="social_share_container_inner">'.
							$add_this_share.
						'</div>
					</div>
					</div>
					';
				}
				else
				{
					echo '<div class="jd_horizontal_social_buttons">';
						echo '<div class="jd_float_left">
								<div class="fb-like" data-href="'.$campaign_link.'" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true">
								</div>
							</div>';
						echo '

						<div class="jd_float_left">
								&nbsp; <div class="g-plus" data-action="share" data-annotation="bubble" data-href="'.$campaign_link.'">
									</div>
						</div>';
					echo '<div class="jd_float_left">
							&nbsp; <a href="https://twitter.com/share" class="twitter-share-button"  data-url="'.$campaign_link.'" data-counturl="'.$campaign_link.'">Tweet</a>
						</div>';
					echo '</div>
						<div class="clearfix"></div>';
				}
			}
			?>
		</div>
	</div>
	<?php
}
?>
<?php
if($this->campaign->donation_form_msg)
{
	$message = $this->campaign->donation_form_msg;
}
else 
{
	if (!$this->showCampaignSelection && strlen(trim(strip_tags($this->campaign->description))))
	{
		$message = $this->campaign->description;
	}
	else
	{
	$message = $this->config->donation_form_msg;
	}
}
if (strlen($message))
{
?>
	<div class="jd-message clearfix"><?php echo $message; ?></div>
<?php
}
if (!$this->userId && $this->config->registration_integration && $this->config->show_login_box)
{
	$actionUrl = JRoute::_('index.php?option=com_users&task=user.login');
	$validateLoginForm = 1;
	?>
	<form method="post" action="<?php echo $actionUrl ; ?>" name="jd-login-form" id="jd-login-form" autocomplete="off" class="form form-horizontal">
		<h3 class="jd-heading"><?php echo JText::_('JD_EXISTING_USER_LOGIN'); ?></h3>
		<div class="control-group">
			<label class="control-label" for="username">
				<?php echo  JText::_('JD_USERNAME') ?><span class="required">*</span>
			</label>
			<div class="controls">
				<input type="text" name="username" id="username" class="input-large validate[required]" value=""/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password">
				<?php echo  JText::_('JD_PASSWORD') ?><span class="required">*</span>
			</label>
			<div class="controls">
				<input type="password" id="password" name="password" class="input-large validate[required]" value="" />
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" value="<?php echo JText::_('JD_LOGIN'); ?>" class="button btn btn-primary" />
			</div>
		</div>
		<h3 class="eb-heading"><?php echo JText::_('JD_NEW_USER_REGISTER'); ?></h3>
		<?php
		if (JPluginHelper::isEnabled('system', 'remember'))
		{
		?>
			<input type="hidden" name="remember" value="1" />
		<?php
		}
		?>
		<input type="hidden" name="return" value="<?php echo base64_encode(JUri::getInstance()->toString()); ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</form>
<?php
}
else
{
	$validateLoginForm = 0;
}
?>
<form method="post" name="os_form" id="os_form" action="<?php echo $url ; ?>" autocomplete="off" class="form form-horizontal" enctype="multipart/form-data">
	<?php
	if (!$this->userId && $this->config->registration_integration)
	{
	$params = JComponentHelper::getParams('com_users');
	$minimumLength = $params->get('minimum_length', 4);
	($minimumLength) ? $minSize = "minSize[4]" : $minSize = "";
	if (!$this->config->show_login_box)
	{
	?>
		<h3 class="eb-heading"><?php echo JText::_('JD_ACCOUNT_INFORMATION'); ?></h3>
	<?php
	}
	?>
	<div class="control-group">
		<label class="control-label" for="username1">
			<?php echo JText::_('JD_USERNAME') ?><span class="required">*</span>
		</label>
		<div class="controls">
			<input type="text" name="username" id="username1" class="input-large validate[required,ajax[ajaxUserCall]]" value="<?php echo $this->input->get('username', '', 'string'); ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password1">
			<?php echo  JText::_('JD_PASSWORD') ?><span class="required">*</span>
		</label>
		<div class="controls">
			<input type="password" name="password1" id="password1" class="input-large validate[required,<?php echo $minSize;?>]" value=""/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password2">
			<?php echo  JText::_('JD_RETYPE_PASSWORD') ?><span class="required">*</span>
		</label>
		<div class="controls">
			<input type="password" name="password2" id="password2" class="input-large validate[required,equals[password1]]" value="" />
		</div>
	</div>
	<?php
	}
	?>
	<div class="control-group">
		<h3 class="jd-heading"><?php echo JText::_('JD_DONOR_INFO'); ?></h3>
	</div>
	<?php
	if ($this->config->use_campaign)
	{
		if ($this->showCampaignSelection)
		{
			//Campaign has been selected from the module or from campaigns page, so just display campaign title
		}
		else
		{
		?>
			<div class="control-group">
				<label class="control-label" for="campaign_id">
					<?php echo JText::_('JD_CAMPAIGN');?>
				</label>
				<div class="controls">
					<?php echo $this->campaign->title; ?>
				</div>
			</div>
		<?php
		}
	}
	$fields = $this->form->getFields();
	if (isset($fields['state']))
	{
		$selectedState = $fields['state']->value;
	}
	foreach ($fields as $field)
	{
		if ($field->name =='email')
		{
			if ($this->userId || !$this->config->registration_integration)
			{
				//We don't need to perform ajax email validate in this case, so just remove the rule
				$cssClass = $field->getAttribute('class');
				$cssClass = str_replace(',ajax[ajaxEmailCall]', '', $cssClass);
				$field->setAttribute('class', $cssClass);
			}
		}
		echo $field->getControlGroup();
	}
	if ($this->config->pay_payment_gateway_fee)
	{
	?>
		<div class="control-group">
			<label class="control-label" for="pay_payment_gateway_fee">
				<?php echo  JText::_('JD_PAY_PAYMENT_GATEWAY_FEE'); ?>
			</label>
			<?php
			if (version_compare(JVERSION, '3.0', 'lt'))
			{
			?>
				<div class="controls">
					<?php echo $this->lists['pay_payment_gateway_fee']; ?>
				</div>
			<?php
			}
			else
			{
				echo $this->lists['pay_payment_gateway_fee'];
			}
			?>
		</div>
	<?php
	}
	if ($this->config->enable_hide_donor)
	{
	?>
		<div class="control-group">
			<label class="control-label" for="hide_me">
				<?php echo  JText::_('JD_HIDE_DONOR'); ?>
			</label>
			<div class="controls">
				<input type="checkbox" class="input-large" name="hide_me" value="1" size="40" <?php if ($this->hideMe) echo ' checked="checked"' ; ?> />
			</div>
		</div>
	<?php
	}
	?>
	<div class="control-group">
		<h3 class="jd-heading"><?php echo JText::_('JD_DONATION_INFORMATION'); ?></h3>
	</div>
	<?php
	if ($this->config->enable_recurring)
	{
		if ($this->campaignId)
		{
			if ($this->campaign->donation_type == 0 && $this->method->getEnableRecurring())
			{
				$style = '';
			}
			else
			{
				$style = ' style="display:none;"' ;
			}
		}
		else
		{
			if ($this->method->getEnableRecurring())
			{
				$style = '';
			}
			else
			{
				$style = ' style="display:none;"' ;
			}
		}
	?>
	<div class="control-group" id="donation_type" <?php echo $style; ?>>
		<label class="control-label" for="donation_type">
			<?php echo JText::_('JD_DONATION_TYPE'); ?>
		</label>

		<?php
			if (version_compare(JVERSION, '3.0', 'lt'))
			{
			?>
				<div class="controls">
					<?php echo $this->lists['donation_type']; ?>
				</div>
			<?php
			}
			else
			{
				echo $this->lists['donation_type'];
			}
		?>
	</div>
	<?php
		if ($this->donationType == 'onetime' || !$this->method->getEnableRecurring())
		{
			$style = ' style="display:none" ';
		}
		else
		{
			$style = '';
		}
	?>
	<div class="control-group" id="tr_frequency" <?php echo $style; ?>>
		<label class="control-label" for="r_frequency">
			<?php echo JText::_('JD_FREQUENCY') ; ?>
		</label>
		<div class="controls">
			<?php
				if (count($this->recurringFrequencies) > 1)
				{
					echo $this->lists['r_frequency'];
				}
				else
				{
					$frequency = $this->recurringFrequencies[0];
					switch($frequency)
					{
						case 'd':
							echo JText::_('JD_DAILY');
							break;
						case 'w':
							echo JText::_('JD_WEEKLY');
							break;
						case 'b':
							echo JText::_('JD_BI_WEEKLY');
							break;
						case 'm':
							echo JText::_('JD_MONTHLY');
							break;
						case 'q':
							echo JText::_('JD_QUARTERLY');
							break;
						case 's':
							echo JText::_('JD_SEMI_ANNUALLY');
							break;
						case 'a':
							echo JText::_('JD_ANNUALLY');
							break;
					}
					?>
					<input type="hidden" name="r_frequency" value="<?php echo $frequency; ?>" />
					<?php
				}
			?>
		</div>
	</div>
	<?php
		if ($this->config->show_r_times)
		{
		?>
			<div class="control-group" id="tr_number_donations" <?php echo $style; ?>>
				<label class="control-label" for="r_times">
					<?php echo JText::_('JD_OCCURRENCES') ; ?>
				</label>
				<div class="controls">
					<input type="text" name="r_times" value="<?php echo $this->input->getInt('r_times', null); ?>" class="input-small"/>
				</div>
			</div>
		<?php
		}
	}
?>
	<div class="control-group">
		<label class="control-label">
			<?php echo JText::_('JD_DONATION_AMOUNT'); ?>
		</label>
		<div class="controls" id="amount_container">
			<?php
				$amountSelected = false;
				if ($this->config->donation_amounts)
				{
					$explanations = explode("\r\n", $this->config->donation_amounts_explanation) ;
					$amounts = explode("\r\n", $this->config->donation_amounts);
					if ($this->config->amounts_format == 1)
					{
						for ($i = 0 , $n = count($amounts) ; $i < $n ; $i++)
						{
							$amount = (float)$amounts[$i] ;
							if ($amount == $this->rdAmount)
							{
								$amountSelected = true;
								$checked = ' checked="checked" ' ;
							}
							else
							{
								$checked = '' ;
							}
						?>
							<label>
								<input type="radio" name="rd_amount" class="validate[required] input-large" value="<?php echo $amount; ?>" <?php echo $checked ; ?> onclick="clearTextbox();" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED'); ?>" /><?php echo ' '.DonationHelperHtml::formatAmount($this->config, $amount);?>
								<?php
								if (isset($explanations[$i]) && $explanations[$i])
								{
									echo '   <span class="amount_explaination">[ '.$explanations[$i].' ]</span>  ' ;
								}
								?>
							</label>
						<?php
						}
					}
					else
					{
						$options = array() ;
						$options[] = JHtml::_('select.option', 0, JText::_('JD_AMOUNT')) ;
						for ($i = 0 , $n = count($amounts) ; $i < $n ; $i++)
						{
							$amount = (float)$amounts[$i] ;
							if ($amount == $this->rdAmount)
							{
								$amountSelected = true;
							}
							if (isset($explanations[$i]) && $explanations[$i])
							{
								$options[] = JHtml::_('select.option', $amount, DonationHelperHtml::formatAmount($this->config, $amount)." [$explanations[$i]]") ;
							}
							else
							{
								$options[] = JHtml::_('select.option', $amount, DonationHelperHtml::formatAmount($this->config, $amount)) ;
							}
						}
						echo  $this->config->currency_symbol.'  '.JHtml::_('select.genericlist', $options, 'rd_amount', ' class="validate[required] input-large" onchange="clearTextbox();" ', 'value', 'text', $this->rdAmount).'<br /><br />';
					}
				}
				if ($this->config->display_amount_textbox)
				{
					if ($this->config->donation_amounts)
					{
						$placeHolder = JText::_('JD_OTHER_AMOUNT');
					}
					else
					{
						$placeHolder = '';
					}
					if ($amountSelected)
					{
						$amountCssClass = 'validate[custom[number]'.$amountValidationRules.'] input-small';
					}
					else
					{
						$amountCssClass = 'validate[required,custom[number]'.$amountValidationRules.'] input-small';
					}
					if ($this->config->currency_position == 0)
					{
					?>
						<div class="input-prepend inline-display">
							<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
							<input type="text" placeholder="<?php echo $placeHolder; ?>" class="<?php echo $amountCssClass; ?>" name="amount" value="<?php echo $this->amount;?>" onchange="deSelectRadio();" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage-range-underflow="<?php echo JText::sprintf('JD_MIN_DONATION_AMOUNT_ALLOWED', $this->config->minimum_donation_amount); ?>" data-errormessage-range-overflow="<?php echo JText::sprintf('JD_MAX_DONATION_AMOUNT_ALLOWED', $this->config->maximum_donation_amount); ?>" />
						</div>
					<?php
					}
					else
					{
					?>
						<div class="input-append inline-display">
							<input type="text" placeholder="<?php echo $placeHolder; ?>" class="<?php echo $amountCssClass; ?>" name="amount" value="<?php echo $this->amount;?>" onchange="deSelectRadio();" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage-range-underflow="<?php echo JText::sprintf('JD_MIN_DONATION_AMOUNT_ALLOWED', $this->config->minimum_donation_amount); ?>" data-errormessage-range-overflow="<?php echo JText::sprintf('JD_MAX_DONATION_AMOUNT_ALLOWED', $this->config->maximum_donation_amount); ?>" />
							<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
						</div>
					<?php
					}
				}
			?>
		</div>
	</div>
	<?php
		if ($this->config->currency_selection)
		{
		?>
			<div class="control-group">
				<label class="control-label">
					<?php echo JText::_('JD_CHOOSE_CURRENCY'); ?>
				</label>
				<div class="controls">
					<?php echo $this->lists['currency_code']; ?>
				</div>
			</div>
		<?php
		}
		if (count($this->methods) > 1)
		{
		?>
			<div class="control-group">
				<label class="control-label">
					<?php echo JText::_('JD_PAYMENT_OPTION'); ?>
					<span class="required">*</span>
				</label>
				<div class="controls">
					<?php
						$method = null ;
						for ($i = 0 , $n = count($this->methods); $i < $n; $i++)
						{
							$paymentMethod = $this->methods[$i];
							if ($paymentMethod->getName() == $this->paymentMethod)
							{
								$checked = ' checked="checked" ';
								$method = $paymentMethod ;
							}
							else
							{
								$checked = '';
							}
						?>
							<label>
								<input onclick="changePaymentMethod();" type="radio" name="payment_method" value="<?php echo $paymentMethod->getName(); ?>" <?php echo $checked; ?> /><?php echo JText::_($paymentMethod->getTitle()); ?> <br />
							</label>
						<?php
						}
					?>
				</div>
			</div>
		<?php
		} else
		{
			$method = $this->methods[0] ;
		?>
			<div class="control-group">
				<label class="control-label">
					<?php echo JText::_('JD_PAYMENT_OPTION'); ?>
				</label>
				<div class="controls">
					<?php echo JText::_($method->getTitle()); ?>
				</div>
			</div>
		<?php
		}
		if ($method->getCreditCard())
		{
			$style = '' ;
		}
		else
		{
			$style = 'style = "display:none"';
		}
		?>
		<div class="control-group" id="tr_card_number" <?php echo $style; ?>>
			<label class="control-label"><?php echo  JText::_('AUTH_CARD_NUMBER'); ?><span class="required">*</span></label>
			<div class="controls">
				<input type="text" name="x_card_num" id="x_card_num" class="input-large validate[required,creditCard]" onkeyup="checkNumber(this)" value="<?php echo $this->input->get('x_card_num', '', 'none'); ?>" size="20" />
			</div>
		</div>
		<div class="control-group" id="tr_exp_date" <?php echo $style; ?>>
			<label class="control-label">
				<?php echo JText::_('AUTH_CARD_EXPIRY_DATE'); ?><span class="required">*</span>
			</label>
			<div class="controls">
				<?php echo $this->lists['exp_month'] .'  /  '.$this->lists['exp_year'] ; ?>
			</div>
		</div>
		<div class="control-group" id="tr_cvv_code" <?php echo $style; ?>>
			<label class="control-label">
				<?php echo JText::_('AUTH_CVV_CODE'); ?><span class="required">*</span>
			</label>
			<div class="controls">
				<input type="text" name="x_card_code" class="input-large validate[required,custom[number]]" value="<?php echo $this->input->get('x_card_code', '', 'none'); ?>" size="20" />
			</div>
		</div>
		<?php
			if ($method->getCardType())
			{
				$style = '' ;
			}
			else
			{
				$style = ' style = "display:none;" ' ;
			}
		?>
			<div class="control-group" id="tr_card_type" <?php echo $style; ?>>
				<label class="control-label">
					<?php echo JText::_('JD_CARD_TYPE'); ?><span class="required">*</span>
				</label>
				<div class="controls">
					<?php echo $this->lists['card_type'] ; ?>
				</div>
			</div>
		<?php
			if ($method->getCardHolderName())
			{
				$style = '' ;
			}
			else
			{
				$style = ' style = "display:none;" ' ;
			}
		?>
			<div class="control-group" id="tr_card_holder_name" <?php echo $style; ?>>
				<label class="control-label">
					<?php echo JText::_('JD_CARD_HOLDER_NAME'); ?><span class="required">*</span>
				</label>
				<div class="controls">
					<input type="text" name="card_holder_name" class="input-large validate[required]"  value="<?php echo $this->input->get('card_holder_name', '', 'none'); ?>" size="40" />
				</div>
			</div>
		<?php
		if (DonationHelper::isPaymentMethodEnabled('os_echeck'))
		{
			if ($method->getName() == 'os_echeck')
			{
				$style = '';
			}
			else
			{
				$style = ' style = "display:none;" ';
			}
			?>
			<div class="control-group" id="tr_bank_rounting_number" <?php echo $style; ?>>
				<label class="control-label"><?php echo JText::_('JD_BANK_ROUTING_NUMBER'); ?><span
						class="required">*</span></label>

				<div class="controls"><input type="text" name="x_bank_aba_code"
				                             class="input-large validate[required,custom[number]]"
				                             value="<?php echo $this->input->get('x_bank_aba_code', '', 'none'); ?>"
				                             size="40"/></div>
			</div>
			<div class="control-group" id="tr_bank_account_number" <?php echo $style; ?>>
				<label class="control-label"><?php echo JText::_('JD_BANK_ACCOUNT_NUMBER'); ?><span
						class="required">*</span></label>

				<div class="controls"><input type="text" name="x_bank_acct_num"
				                             class="input-large validate[required,custom[number]]"
				                             value="<?php echo $this->input->get('x_bank_acct_num', '', 'none');; ?>"
				                             size="40"/></div>
			</div>
			<div class="control-group" id="tr_bank_account_type" <?php echo $style; ?>>
				<label class="control-label"><?php echo JText::_('JD_BANK_ACCOUNT_TYPE'); ?><span
						class="required">*</span></label>

				<div class="controls"><?php echo $this->lists['x_bank_acct_type']; ?></div>
			</div>
			<div class="control-group" id="tr_bank_name" <?php echo $style; ?>>
				<label class="control-label"><?php echo JText::_('JD_BANK_NAME'); ?><span
						class="required">*</span></label>

				<div class="controls"><input type="text" name="x_bank_name" class="input-large validate[required]"
				                             value="<?php echo $this->input->get('x_bank_name', '', 'none'); ?>"
				                             size="40"/></div>
			</div>
			<div class="control-group" id="tr_bank_account_holder" <?php echo $style; ?>>
				<label class="control-label"><?php echo JText::_('JD_ACCOUNT_HOLDER_NAME'); ?><span
						class="required">*</span></label>

				<div class="controls"><input type="text" name="x_bank_acct_name" class="input-large validate[required]"
				                             value="<?php echo $this->input->get('x_bank_acct_name', '', 'none'); ?>"
				                             size="40"/></div>
			</div>
		<?php
		}
		if($this->showCaptcha)
		{
		?>
			<div class="control-group">
				<label class="control-label">
					<?php echo JText::_('JD_CAPTCHA'); ?><span class="required">*</span>
				</label>
				<div class="controls">
					<?php echo $this->captcha; ?>
				</div>
			</div>
		<?php
		}
		if ($this->config->accept_term ==1 && $this->config->article_id > 0)
		{
			JHtml::_('behavior.modal', 'a.jd-modal');
			$articleId = $this->config->article_id;
			$db =  JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id, catid')
				->from('#__content')
				->where('id = '. (int) $articleId);
			$db->setQuery($query);
			$article = $db->loadObject();
			//Terms and Conditions
			require_once JPATH_ROOT.'/components/com_content/helpers/route.php' ;
			$termLink = ContentHelperRoute::getArticleRoute($article->id, $article->catid).'&tmpl=component&format=html' ;
			$extra = ' class="jd-modal" ' ;
		?>
			<div class="control-group">
				<label class="checkbox">
					<input type="checkbox" name="accept_term" value="1" class="validate[required]" data-errormessage="<?php echo JText::_('JD_ACCEPT_TERMS');?>" />
					<?php echo JText::_('JD_ACCEPT'); ?>&nbsp;
					<?php
						echo "<a $extra href=\"".JRoute::_($termLink)."\">"."<strong>".JText::_('JD_TERM_AND_CONDITION')."</strong>"."</a>\n";
					?>
				</label>
			</div>
		<?php
		}
	?>
	<div class="form-actions">
		<input type="submit" class="btn btn-primary" name="btnSubmit" id="btn-submit" value="<?php echo  JText::_('JD_PROCESS_DONATION') ;?>" />
	</div>
	<?php
		if (count($this->methods) == 1)
		{
		?>
			<input type="hidden" name="payment_method" value="<?php echo $this->methods[0]->getName(); ?>" />
		<?php
		}
		if (!$this->config->enable_recurring)
		{
		?>
			<input type="hidden" name="donation_type" value="onetime" />
		<?php
		}
		if (!$this->showCampaignSelection)
		{
		?>
			<input type="hidden" name="campaign_id" value="<?php echo $this->campaignId; ?>" />
		<?php
		}
	?>
	<input type="hidden" name="validate_form_login" value="<?php echo $validateLoginForm; ?>" />
	<input type="hidden" name="receive_user_id" value="<?php echo $this->input->getInt('receive_user_id'); ?>" />
	<input type="hidden" name="amounts_format" value="<?php echo $this->config->amounts_format; ?>" />
	<input type="hidden" name="field_campaign" value="<?php echo $this->config->field_campaign; ?>" />
	<input type="hidden" name="amount_by_campaign" value="<?php echo $this->config->amount_by_campaign; ?>" />
	<input type="hidden" name="enable_recurring" value="<?php echo $this->config->enable_recurring; ?>" />
	<input type="hidden" name="count_method" value="<?php echo count($this->methods); ?>" />
	<input type="hidden" name="current_campaign" value="<?php echo $this->campaignId; ?>" />
	<input type="hidden" name="donation_page_url" value="<?php echo $this->donationPageUrl; ?>" />
	<input type="hidden" name="task" value="donation.process">
	<?php echo JHtml::_( 'form.token' ); ?>
	<script type="text/javascript">
		var amountInputCssClasses = '<?php echo "validate[required,custom[number] $amountValidationRules ] input-small"; ?>';
		<?php echo os_payments::writeJavascriptObjects() ; ?>
		JD.jQuery(function($){
			$(document).ready(function(){
				$("#os_form").validationEngine('attach', {
					onValidationComplete: function(form, status){
						if (status == true) {
							form.on('submit', function(e) {
								e.preventDefault();
							});

							form.find('#btn-submit').prop('disabled', true);

							if (typeof stripePublicKey !== 'undefined')
							{
								if($('input:radio[name^=payment_method]').length)
								{
									var paymentMethod = $('input:radio[name^=payment_method]:checked').val();
								}
								else
								{
									var paymentMethod = $('input[name^=payment_method]').val();
								}

								if (paymentMethod.indexOf('os_stripe') == 0)
								{
								 Stripe.card.createToken({
								  number: $('#x_card_num').val(),
								  cvc: $('#x_card_code').val(),
								  exp_month: $('select[name^=exp_month]').val(),
								  exp_year: $('select[name^=exp_year]').val(),
								  name: $('#card_holder_name').val()
								 }, stripeResponseHandler);

								 return false;
								}
							}

							return true;
						}
						return false;
					}
				});

				if($("[name*='validate_form_login']").val() == 1)
				{
					JDVALIDATEFORM("#jd-login-form");
				}
				<?php
					if (isset($fields['state']) && JString::strtolower($fields['state']->type) == 'state')
					{
					?>
						buildStateField('state', 'country', '<?php echo $selectedState; ?>');
					<?php
					}
				?>
			})
		});
	</script>
</form>
</div>
<?php
	if ($this->config->amount_by_campaign)
	{
		$rowCampaigns  = $this->rowCampaigns ;
		for ($j = 0 , $m = count($rowCampaigns) ; $j < $m ; $j++)
		{
		$rowCampaign = $rowCampaigns[$j] ;
		?>
			<div id="campaign_<?php echo $rowCampaign->id; ?>" style="display: none;">
			<?php
			$explanations = explode("\r\n", $rowCampaign->amounts_explanation) ;
			$amounts = explode("\r\n", $rowCampaign->amounts);
			$amountSelected = false;
			if ($this->config->amounts_format == 1)
			{
				for ($i = 0 , $n = count($amounts) ; $i < $n ; $i++)
				{
					$amount = (float)$amounts[$i] ;
					if ($amount == $this->rdAmount)
					{
						$amountSelected = true;
						$checked = ' checked="checked" ' ;
					}
					else
					{
						$checked = '' ;
					}
				?>
					<input type="radio" name="rd_amount" class="input-large" value="<?php echo $amount; ?>" <?php echo $checked ; ?> onclick="clearTextbox();" /><?php echo ' '.DonationHelperHtml::formatAmount($this->config, $amount) ;?>
					<?php
						if (isset($explanations[$i]) && $explanations[$i])
						{
							echo '   <span class="amount_explaination">[ '.$explanations[$i].' ]</span>  ' ;
						}
					?>
				<?php
				}
			}
			else
			{
				$options = array() ;
				$options[] = JHtml::_('select.option', 0, JText::_('JD_DONATION_AMOUNT')) ;
				for ($i = 0 , $n = count($amounts) ; $i < $n ; $i++)
				{
					$amount = (float)$amounts[$i] ;
					if ($amount == $this->rdAmount)
					{
						$amountSelected = true;
					}
					if (isset($explanations[$i]) && $explanations[$i])
					{
						$options[] = JHtml::_('select.option', $amount, DonationHelperHtml::formatAmount($this->config, $amount)." [$explanations[$i]]");
					}
					else
					{
						$options[] = JHtml::_('select.option', $amount, DonationHelperHtml::formatAmount($this->config, $amount));
					}
				}
				echo  $this->config->currency_symbol.'  '.JHtml::_('select.genericlist', $options, 'rd_amount', ' class="input-large" onchange="clearTextbox();" ', 'value', 'text', $this->rdAmount).'<br /><br />';
			}
			if ($this->config->display_amount_textbox)
			{
				if ($amountSelected)
				{
					$amountCssClass = 'validate[custom[number]'.$amountValidationRules.'] input-small';
				}
				else
				{
					$amountCssClass = 'validate[required,custom[number]'.$amountValidationRules.'] input-small';
				}
				if ($rowCampaign->amounts)
				{
					$placeHolder = JText::_('JD_OTHER_AMOUNT');
				}
				else
				{
					$placeHolder = '';
				}

				if ($this->config->currency_position == 0)
				{
				?>
					<div class="input-prepend inline-display">
						<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
						<input type="text" placeholder="<?php echo $placeHolder; ?>" class="<?php echo $amountCssClass; ?>" name="amount" value="<?php echo $this->amount;?>" onchange="deSelectRadio();" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage-range-underflow="<?php echo JText::sprintf('JD_MIN_DONATION_AMOUNT_ALLOWED', $this->config->minimum_donation_amount); ?>" data-errormessage-range-overflow="<?php echo JText::sprintf('JD_MAX_DONATION_AMOUNT_ALLOWED', $this->config->maximum_donation_amount); ?>" />
					</div>
				<?php
				}
				else
				{
				?>
					<div class="input-append inline-display">
						<input type="text" placeholder="<?php echo $placeHolder; ?>" class="<?php echo $amountCssClass; ?>" name="amount" value="<?php echo $this->amount;?>" onchange="deSelectRadio();" data-errormessage="<?php echo JText::_('JD_AMOUNT_IS_REQUIRED');?>" data-errormessage-range-underflow="<?php echo JText::sprintf('JD_MIN_DONATION_AMOUNT_ALLOWED', $this->config->minimum_donation_amount); ?>" data-errormessage-range-overflow="<?php echo JText::sprintf('JD_MAX_DONATION_AMOUNT_ALLOWED', $this->config->maximum_donation_amount); ?>" />
						<span class="add-on"><?php echo $this->config->currency_symbol;?></span>
					</div>
				<?php
				}
			}
		?>
		</div>
		<?php
		}
	}
?>
