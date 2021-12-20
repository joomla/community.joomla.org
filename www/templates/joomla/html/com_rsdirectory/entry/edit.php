<?php
/**
 * @package	RSDirectory!
 * @copyright	(c) 2013 - 2019 RSJoomla!
 * @link		https://www.rsjoomla.com
 * @license	GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', '.rsdir-field-wrapper select');
?>

<div class="rsdir">
	<div class="row-fluid">
		<div class="item-page <?php echo !empty($this->pageclass_sfx) ? htmlspecialchars($this->pageclass_sfx) : ''; ?>">
				
			<?php if ( !empty($this->params) ) { ?> 
				<?php if ( $this->params->get('show_page_heading') ) { ?>
				<div class="page-header">
					<h1><?php echo $this->escape( $this->params->get('page_heading') ); ?></h1>
				</div>
				<?php } ?>
			<?php } ?>
				
			<?php if ($this->display_form && $this->form && !$this->user->id) { ?>
			<?php if ($this->params->get('display_login_form', 1)) { ?>
			<div class="alert alert-info">
				<?php echo JText::_('COM_RSDIRECTORY_ENTRY_GUEST'); ?>
			</div>
				
			<form action="<?php echo JRoute::_( 'index.php', true, false ); ?>" method="post">
				<fieldset>
					<legend><?php echo JText::_('COM_RSDIRECTORY_LOGIN_FORM'); ?></legend>
						
					<div class="control-group">
						<label class="control-label" for="rsdir-login-username"><?php echo JText::_('JGLOBAL_USERNAME'); ?><span class="star">&nbsp;*</span></label>
						<div class="controls">
							<input id="rsdir-login-username" type="text" name="username" placeholder="<?php echo JText::_('JGLOBAL_USERNAME'); ?>" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="rsdir-login-password"><?php echo JText::_('JGLOBAL_PASSWORD'); ?><span class="star">&nbsp;*</span></label>
						<div class="controls">
							<input id="rsdir-login-password" type="password" name="password" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>" />
						</div>
					</div>
					<?php if (count($this->twofactormethods) > 1) { ?>
						<div class="control-group">
							<label class="control-label" for="rsdir-login-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?><span class="star">&nbsp;*</span></label>
							<div class="controls">
								<input id="rsdir-login-secretkey" autocomplete="one-time-code" type="text" name="secretkey" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" />
							</div>
						</div>
					<?php } ?>
					<div class="control-group">
						<div class="controls">
							<label class="checkbox">
								<input type="checkbox" name="remember" value="yes"  /> <?php echo JText::_('JGLOBAL_REMEMBER_ME'); ?>
							</label>
							<button type="submit" class="btn btn-primary"><?php echo JText::_('JLOGIN'); ?></button>
						</div>
					</div>
				</fieldset>
				<div>
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="user.login" />
					<input type="hidden" name="return" value="<?php echo base64_encode($this->login_return);?>" />
					<?php echo JHtml::_('form.token'); ?>
				</div>
			</form>
			<?php } ?>
			<?php } ?>
				
			<form id="adminForm" class="form-horizontal" action="<?php echo htmlspecialchars( JUri::getInstance()->toString() ); ?>" method="post" enctype="multipart/form-data">
					
				<div class="clearfix">
						
					<?php
						
					if ($this->display_form)
					{
						echo $this->loadTemplate('form');
					}
					else
					{
						echo $this->loadTemplate('category');
					}
						
					?>
						
					<div>
						<?php echo JHTML::_('form.token') . "\n"; ?>
					</div>
						
				</div><!-- .clearfix -->
					
			</form>
				
		</div><!-- .item-page -->
	</div><!-- .row-fluid -->
</div><!-- .rsdir -->