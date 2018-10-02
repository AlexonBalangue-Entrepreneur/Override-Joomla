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

jimport('joomla.html.html.bootstrap');

$app  = JFactory::getApplication();
$doc  = JFactory::getDocument();
$user = JFactory::getUser();

$pathway = $app->getPathway();
$pathway->addItem(JText::_('COM_RDSUBS_PRIVACY_STATEMENT'));

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');

?>

<h1><?php echo JText::_('COM_RDSUBS_PRIVACY_STATEMENT'); ?></h1>

<?php
echo RDSubsMessage::getInstance('privacy-header')
	->user(JFactory::getUser()->id)
	->getBody();
?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="<?php echo (JFactory::getUser()->guest || ! RDSubsConfig::get('gdpr_show_privacy_tab')) ? 'active' : null; ?>">
			<a href="#privacy" data-toggle="tab"><?php echo JText::_('COM_RDSUBS_PRIVACY_STATEMENT'); ?></a></li>
		<li><a href="#terms" data-toggle="tab"><?php echo JText::_('COM_RDSUBS_TERMS_AND_CONDITIONS'); ?></a></li>
	</ul>

	<div class="tab-content">

		<div class="tab-pane <?php echo (JFactory::getUser()->guest || ! RDSubsConfig::get('gdpr_show_privacy_tab')) ? 'active' : null; ?>>" id="privacy">

			<?php
			echo RDSubsMessage::getInstance('privacy-statement')
				->user(JFactory::getUser()->id)
				->getBody();
			?>

		</div>

		<div class="tab-pane" id="terms">

			<?php
			echo RDSubsMessage::getInstance('terms-and-conditions')
				->user(JFactory::getUser()->id)
				->getBody();
			?>

		</div>

	</div>

</div>

