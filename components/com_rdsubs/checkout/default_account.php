<?php
/**
 * @package		Joomla
 * @subpackage  RD-Subscriptions
 * @version	    2.7.2 
 * @release	    2018-07-10 
 *
 * @copyright	2018 RD-Media || RDProductions All rights reserved.
 * @author	    Robert Dam
 * @license		GNU GENERAL PUBLIC LICENSE V2
 * @license	    http://rd-media.org/license.php 
 */

defined('_JEXEC') or die();

## Obtain user information.
$userid = JFactory::getUser()->id;

?>
<h2 class="well-header">
	<?php echo JText::_('COM_RDSUBS_YOUR_USER_ACCOUNT'); ?>
</h2>

<?php if ( ! $userid) : ?>
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="accountLoginForm" id="accountLoginForm" class="form-vertical">
		<div class="radio">
			<label style="margin-bottom:5px;">
				<input type="radio" name="logincreateoption" value="0" checked="checked">
				<?php echo JText::_('COM_RDSUBS_NEW_TO_SITE'); ?>
			</label>
		</div>

		<div class="radio">
			<label>
				<input type="radio" name="logincreateoption" value="1">
				<?php echo JText::_('COM_RDSUBS_RETURNING_CUSTOMER'); ?>
			</label>
		</div>

		<div id="login_account" class="login_account_box" style="display:none;">

			<div class="form-group input-group input-prepend">
				<span class="input-group-addon add-on">
					<span class="icon-user"></span>
				</span>
				<input type="text" name="username" id="username" class="form-control input input-block"
				       placeholder="<?php echo JText::_('COM_RDSUBS_USERNAME'); ?>">
			</div>

			<div class="form-group input-group input-prepend">
				<span class="input-group-addon add-on">
					<span class="icon-lock"></span>
				</span>
				<input type="password" name="password" id="password" class="form-control input input-block"
				       placeholder="<?php echo JText::_('COM_RDSUBS_PASSWORD'); ?>" value="">
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-success"><?php echo JText::_('COM_RDSUBS_LOGIN'); ?></button>
			</div>

			<input type="hidden" name="option" value="com_rdsubs" />
			<input type="hidden" name="view" value="signup" />
			<input type="hidden" name="task" value="login" />
		</div>
	</form>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="checkoutForm" id="checkoutForm">

	<?php if ( ! $userid) : ?>

		<div id="create_account">

			<div class="form-group">
				<label class="label_class usernameCheck" id="username_taken"><?php echo JText::_('COM_RDSUBS_PREFERRED_USERNAME'); ?> *</label>
				<div class="input-group input-prepend">
					<span class="input-group-addon add-on">
						<span class="icon-user"></span>
					</span>
					<input name="username" id="username" type="text" class="form-control input input-block required"
					       value="<?php echo isset($this->client->username) ? $this->client->username : ''; ?>" />
				</div>
			</div>

			<div class="form-group">
				<label class="label_class"><?php echo JText::_('COM_RDSUBS_PREFERRED_PASSWORD'); ?> *</label>

				<div class="input-group input-prepend">
					<span class="input-group-addon add-on">
						<span class="icon-lock"></span>
					</span>
					<input name="password" id="password" type="password" class="form-control input input-block required" />
				</div>
			</div>

			<div class="form-group">
				<label class="label_class"><?php echo JText::_('COM_RDSUBS_RETYPE_PASSWORD'); ?> *</label>

				<div class="input-group input-prepend">
					<span class="input-group-addon add-on">
						<span class="icon-lock"></span>
					</span>
					<input name="password2" id="password2" type="password" class="form-control input input-block required" />
				</div>
			</div>
		</div>

	<?php endif; ?>

	<?php
	$layout = new JLayoutFile('account_fields');
	echo $layout->render([
		'client'         => $this->client,
		'config'         => $this->config,
		'countries'      => $this->lists['country'],
		'country_states' => $this->lists['country_states'],
	]);
	?>

	<?php
	JPluginHelper::importPlugin('rdmedia');
	$dispatcher = JEventDispatcher::getInstance();
	$dispatcher->trigger('onShowCheckoutView', [$this->items]);
	?>

	<input type="hidden" name="option" value="com_rdsubs" />
	<input type="hidden" name="view" value="signup" />
	<input type="hidden" name="payment_method" value="" />
	<input type="hidden" name="accepted_tos" id="accepted_tos" value="" />

	<?php if ($userid): ?>
		<input type="hidden" name="userid" value="<?php echo $userid; ?>" />
		<input type="hidden" name="id" value="<?php echo isset($this->client->id) ? $this->client->id : ''; ?>" />
		<input type="hidden" name="task" value="checkout" />
	<?php else: ?>
		<input type="hidden" name="task" value="register" />
	<?php endif; ?>

	<?php echo JHtml::_('form.token'); ?>
</form>
