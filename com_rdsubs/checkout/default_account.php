<?php
/**
 * @package		Joomla
 * @subpackage  RD-Subscriptions
 * @version	    2.5.3 
 * @release	    2017-11-07 
 *
 * @copyright	2017 RD-Media || RDProductions All rights reserved.
 * @author	    Robert Dam
 * @license		GNU GENERAL PUBLIC LICENSE V2
 * @license	    http://rd-media.org/license.php 
 */

defined('_JEXEC') or die();

## Obtain user information.
$userid = JFactory::getUser()->id;

?>
<h2 class="text-primary">
	<?php echo JText::_('COM_RDSUBS_YOUR_USER_ACCOUNT'); ?>
</h2>

<?php if ( ! $userid) : ?>
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="accountLoginForm" id="accountLoginForm" class="form-vertical">
		<div class="form-check">
		  <input class="form-check-input" type="checkbox" name="logincreateoption" checked="checked" value="0" id="logincreateoption">
		  <label class="form-check-label" for="logincreateoption">
			<?php echo JText::_('COM_RDSUBS_NEW_TO_SITE'); ?>
		  </label>
		</div>
		
		<div class="form-check">
		  <input class="form-check-input" type="checkbox" name="logincreateoption" value="1" id="logincreateoption">
		  <label class="form-check-label" for="logincreateoption">
			<?php echo JText::_('COM_RDSUBS_RETURNING_CUSTOMER'); ?>
		  </label>
		</div>

  <div class="form-row align-items-center">
    <div class="col-auto">
      <label class="sr-only" for="username">Username</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fal fa-user fa-2x"></i></div>
        </div>
        <input type="text" name="username" class="form-control" id="username" placeholder="<?php echo JText::_('COM_RDSUBS_USERNAME'); ?>">
      </div>
    </div>
    <div class="col-auto">
      <label class="sr-only" for="password">Username</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fal fa-lock-alt fa-2x"></i></div>
        </div>
        <input type="password" name="password" class="form-control" id="password" placeholder="<?php echo JText::_('COM_RDSUBS_PASSWORD'); ?>" value="">
      </div>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-outline-success mb-2"><?php echo JText::_('COM_RDSUBS_LOGIN'); ?></button>
    </div>
  </div>

			<input type="hidden" name="option" value="com_rdsubs" />
			<input type="hidden" name="view" value="signup" />
			<input type="hidden" name="task" value="login" />
	
	</form>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="checkoutForm" id="checkoutForm">

	<?php if ( ! $userid) : ?>

  <div class="form-row align-items-center">
    <div class="col-auto">
      <label for="username"><?php echo JText::_('COM_RDSUBS_PREFERRED_USERNAME'); ?>*</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fal fa-user fa-2x"></i></div>
        </div>
        <input type="text" name="username" class="form-control" id="username" placeholder="<?php echo JText::_('COM_RDSUBS_USERNAME'); ?>" value="<?php echo isset($this->client->username) ? $this->client->username : ''; ?>" required>
      </div>
    </div>
    <div class="col-auto">
      <label for="password"><?php echo JText::_('COM_RDSUBS_PREFERRED_PASSWORD'); ?>*</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fal fa-lock-alt fa-2x"></i></div>
        </div>
        <input type="password" name="password" class="form-control" id="password" placeholder="<?php echo JText::_('COM_RDSUBS_PASSWORD'); ?>" value="" required>
      </div>
    </div>
    <div class="col-auto">
      <label for="password2"><?php echo JText::_('COM_RDSUBS_RETYPE_PASSWORD'); ?>*</label>
      <div class="input-group mb-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fal fa-lock-alt fa-2x"></i></div>
        </div>
        <input type="password" name="password2" class="form-control" id="password2" placeholder="<?php echo JText::_('COM_RDSUBS_RETYPE_PASSWORD'); ?>" value="" required>
      </div>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-outline-success mb-2"><?php echo JText::_('COM_RDSUBS_LOGIN'); ?></button>
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
