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

require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/rdsubs.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/image.php';
require_once JPATH_ADMINISTRATOR . '/components/com_rdsubs/helpers/message.php';

$app     = JFactory::getApplication();
$pathway = $app->getPathway();
$user    = JFactory::getUser();
$userid  = $user->id;
## Get the global DB session
$session = JFactory::getSession();

$pathway->addItem($this->item->name);

## We are in J3, load the bootstrap!
jimport('joomla.html.html.bootstrap');

## Get document type and add it.
$document = JFactory::getDocument();

$title = ! empty($this->item->metatitle) ? $this->item->metatitle : $this->item->name;

if (empty($title))
{
	$sitetitle = $app->get('sitename');
}
elseif ($app->get('sitename_pagetitles', 0) == 1)
{
	// Put the site name before the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
}
elseif ($app->get('sitename_pagetitles', 0) == 2)
{
	// Put the site name after the page title.
	$sitetitle = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
}
else
{
	// Nothing in configuration:
	$sitetitle = $this->item->name;
}

## Meta data & description
if ($this->item->metakey != '')
{
	$document->setMetadata('keywords', $this->item->metakey);
}

if ($this->item->metadesc != '')
{
	$document->setDescription($this->item->metadesc);
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

JHtml::script('com_rdsubs/script.js', false, true);

$link = JRoute::_('index.php?option=com_rdsubs&view=order&task=product&id=' . $this->item->id);

JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
?>

<div class="rdsubs rdsubs-product">

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'above', 'active' => 'products']);
	?>

	<?php
	echo RDSubsMessage::getInstance('product-header')
	                  ->user(JFactory::getUser()->id)
	                  ->getBody();
	?>

	<?php
	$layout = new JLayoutFile('menu');
	echo $layout->render(['position' => 'under', 'active' => 'products']);
	?>

	<div class="rows">
		<div class="col-sm-4 span4">
			<div class="rdsubs-image-container">
				<img src="<?php echo RDSubsImage::getUrl($this->item->id); ?>" class="rdsubs-image">
			</div>
		</div>

		<div class="col-sm-8 span8">
			<h1><?php echo $this->item->name; ?></h1>

			<?php if ($this->config->show_teaser_on_product) : ?>
				<div class="rdsubs-teaser hidden-phone">
					<p><?php echo $this->item->teaser; ?></p>
				</div>
			<?php endif; ?>

			<?php if ($this->item->price > 0): ?>
				<span class="rdsubs-price">
					<?php echo RDSubsPrice::_($this->item->price); ?>
				</span>
			<?php endif; ?>

		</div>
	</div>

	<div class="clearfix"></div>

	<hr class="hr_styling">

	<div class="rdsubs-description rdsubs-description-short">
		<?php echo JHtml::_('content.prepare', $this->item->short_description); ?>
	</div>

	<div class="clearfix"></div>

	<div class="buttons_main">
		<?php if ($this->item->price > 0) : ?>
			<a href="<?php echo $link; ?>" class="btn btn-success btn-large">
				<span class="icon-basket icon-white"></span>
				<?php echo JText::_('COM_RDSUBS_BUY_NOW'); ?>
				<?php echo RDSubsPrice::_($this->item->price); ?>
			</a>
		<?php else: ?>
			<a href="<?php echo $link; ?>" class="btn btn-success btn-large">
				<span class="icon-basket icon-white"></span>
				<?php echo JText::_('COM_RDSUBS_FREE_SUBSCRIPTION'); ?>
			</a>
		<?php endif; ?>
	</div>

	<div class="buttons_extra">
		<?php if ($this->item->link_to_download) : ?>
			<a href="<?php echo $this->item->link_to_download; ?>" target="_self" class="btn btn-primary"><?php echo $this->item->text_on_downloadbutton; ?></a>
		<?php endif; ?>

		<?php if ($this->item->link_demo): ?>
			<a href="<?php echo $this->item->link_demo; ?>" target="_blank" class="btn btn-default">
				<?php echo JText::_('COM_RDSUBS_DEMO_BUTTON'); ?></a>
		<?php endif; ?>

		<?php if ($this->item->link_to_review) : ?>
			<a href="<?php echo $this->item->link_to_review; ?>" target="_blank" class="btn btn-default">
				<?php echo JText::_('COM_RDSUBS_REVIEW_COMPONENT'); ?></a>
		<?php endif; ?>

		<?php if ($this->item->link_to_support): ?>
			<a href="<?php echo $this->item->link_to_support; ?>" target="_self" class="btn btn-default"><?php echo JText::_('COM_RDSUBS_PRESALE_QUESTION'); ?></a>
		<?php endif; ?>

		<?php if ( ! empty($this->item->custom_buttons)) : ?>
			<?php
			$buttons = explode(PHP_EOL, $this->item->custom_buttons);

			foreach ($buttons as $button)
			{
				if (strpos($button, '|') === false)
				{
					continue;
				}

				$button = explode('|', $button);
				$class  = 'btn' . (! empty($button['3']) ? ' ' . $button['3'] : '');

				echo '<a href="' . $button['1'] . '"'
					. (! empty($button['2']) ? ' target="' . $button['2'] . '"' : '')
					. ' class="' . $class . '"'
					. (! empty($button['3']) ? ' style="' . $button['3'] . '"' : '')
					. '">'
					. $button['0']
					. '</a>';
			}
			?>
		<?php endif; ?>
	</div>

	<div class="clearfix"></div>

	<div class="rdsubs-description rdsubs-description-full">
		<?php echo JHtml::_('content.prepare', $this->item->description); ?>
	</div>

	<div class="clearfix"></div>

	<?php if (count($this->crossell) > 0) : ?>
		<hr />

		<h2 class="product_page"><?php echo JText::_('COM_RDSUBS_RELATED_PRODUCTS'); ?></h2>

		<?php

		$layout = new JLayoutFile('product_list');
		echo $layout->render([
			'items'           => $this->crossell,
			'class'           => 'related_products_box',
			'active_products' => (new \RDMedia\User)->getActiveSubsscriptions(),
			'num_columns'     => $this->params->get('num_columns', $this->config->num_columns),
		]);
		?>
	<?php endif; ?>

</div>
