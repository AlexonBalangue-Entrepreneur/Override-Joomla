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

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';

$app     = JFactory::getApplication();
$pathway = $app->getPathway();
$pathway->addItem(JText::_('COM_RDSUBS_MYPROFILE'));

## Get document type and add it.
$document = JFactory::getDocument();

if ($app->get('sitename_pagetitles', 0) == 1)
{
	// Put the site name before the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), JText::_('COM_RDSUBS_MYPROFILE'));
}
elseif ($app->get('sitename_pagetitles', 0) == 2)
{
	// Put the site name after the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', JText::_('COM_RDSUBS_MYPROFILE'), $app->get('sitename'));
}
else
{
	// Nothing in configuration:
	$sitetitle = JText::_('COM_RDSUBS_MYPROFILE');
}

## Setting the page title.
$document->setTitle($sitetitle);

if ($this->config->load_bootstrap)
{
	JHtml::_('bootstrap.framework');
	JHtmlBootstrap::loadCss();
}
if ($this->config->load_stylesheet)
{
	JHtml::stylesheet('com_rdsubs/style.min.css', false, true);
}

## Obtain user information.
$userid = JFactory::getUser()->id;

$script = "
	var rdsubs_root = '" . JUri::base() . "';
	var vat_countries = " . json_encode($this->vat_countries) . ";
	var rdsubs_lang_vat_number_checking = '" . addslashes(JText::_('COM_RDSUBS_VAT_NUMBER_CHECKING')) . "';
	var rdsubs_lang_vat_number_invalid = '" . addslashes(JText::_('COM_RDSUBS_VAT_NUMBER_INVALID')) . "';
";
JFactory::getDocument()->addScriptDeclaration($script);
JHtml::script('com_rdsubs/script.js', false, true);
JHtml::script('com_rdsubs/profile.js', false, true);

?>

<div class="rdsubs rdsubs-myprofile">

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'above']);
	?>

	<h1><?php echo JText::_('COM_RDSUBS_MYPROFILE'); ?></h1>

	<?php
	echo RDSubsMessage::getInstance('myprofile-header')
	                  ->user(JFactory::getUser()->id)
	                  ->getBody();
	?>

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'under']);
	?>

	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="profileForm" id="profileForm" class="form-vertical">

		<div class="row-fluid rdsubs-minwidth-800">
			<div class="col-md-6 span6">
				<div class="control-group pull-right">
					<button type="submit" class="btn btn-success" type="button">
						<span class="icon-ok icon-white"></span>
						<?php echo JText::_('COM_RDSUBS_SAVE_PROFILE'); ?>
					</button>
				</div>

				<div class="clearfix"></div>

				<div class="well">
					<?php
					$layout = new JLayoutFile('account_fields');
					echo $layout->render([
						'client'         => $this->client,
						'config'         => $this->config,
						'countries'      => $this->lists['country'],
						'country_states' => $this->lists['country_states'],
					]);
					?>

					<button type="submit" class="btn btn-success btn-block btn-large" type="button">
						<span class="icon-ok icon-white"></span>
						<?php echo JText::_('COM_RDSUBS_SAVE_PROFILE'); ?>
					</button>
				</div>
			</div>
		</div>

		<div class="clearfix"></div>

		<input type="hidden" name="option" value="com_rdsubs" />
		<input type="hidden" name="view" value="myprofile" />

		<input type="hidden" name="userid" value="<?php echo $userid; ?>" />
		<input type="hidden" name="id" value="<?php echo $this->client->id; ?>" />
		<input type="hidden" name="task" value="storeProfile" />

		<?php echo JHtml::_('form.token'); ?>
	</form>

</div>
