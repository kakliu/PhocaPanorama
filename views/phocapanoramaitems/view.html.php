<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.view' );
 
class PhocaPanoramaCpViewPhocaPanoramaItems extends JViewLegacy
{

	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	
	function display($tpl = null) {
		
		$this->t			= PhocaPanoramaUtils::setVars('item');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$this->t['panoramapathrel']	= JURI::root().'phocapanorama/';
		$this->t['panoramapathabs']	= JPATH_ROOT .'/phocapanorama/';
		


		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[$item->catid][] = $item->id;
		}
		
		JHTML::stylesheet( $this->t['s'] );

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['tasks'].'.php';
		$state	= $this->get('State');
		$class	= ucfirst($this->t['tasks']).'Helper';
		$canDo	= $class::getActions($this->t, $state->get('filter.item_id'));
		$user  = JFactory::getUser();
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title( JText::_($this->t['l'].'_ITEMS'), 'image' );
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew( $this->t['task'].'.add','JTOOLBAR_NEW');
		
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList($this->t['task'].'.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.create')) {
			//JToolBarHelper::divider();
			//JToolBarHelper::custom( $this->t['task'].'.copyquick','copy.png', '', $this->t['l'].'_QUICK_COPY', true);
			//JToolBarHelper::custom( $this->t['task'].'.copy','copy.png', '', $this->t['l'].'_COPY', true);
		}
		
		if ($canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::custom($this->t['tasks'].'.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom($this->t['tasks'].'.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);

		}

		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList( JText::_( $this->t['l'].'_WARNING_DELETE_ITEMS' ), $this->t['tasks'].'.delete', $this->t['l'].'_DELETE');
		}
		
		// Add a batch button
		if ($user->authorise('core.edit'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');
			$dhtml = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.'.$this->t['c'], true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> JText::_($this->t['l'] . '_TITLE'),
			'a.folder' 		=> JText::_($this->t['l'] . '_FOLDER'),
			'a.published' 	=> JText::_($this->t['l'] . '_PUBLISHED'),
			'category_id' 	=> JText::_($this->t['l'] . '_CATEGORY'),
			'a.hits' 		=> JText::_($this->t['l'] . '_HITS'),
			'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>