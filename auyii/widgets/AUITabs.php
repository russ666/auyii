<?php

class AUITabs extends CWidget
{
	/**
	 * @var string display tabs 'horizontal' (default) or 'vertical'
	 */
	public $display = 'horizontal';
	/**
	 * @var bool Disables the tab set, so the triggers behave as links
	 */
	public $disabled = false;
	/**
	 * @var string active tab id
	 */
	public $activeTab;
	/**
	 * @var array Tabs menu, array('tabId' => 'tabName')
	 */
	public $tabs = array();
	/**
	 * @var array Panes content, array('tabId' => 'paneContent')
	 */
	public $panes = array();
	/**
	 * @var array additional options
	 */
	public $htmlOptions = array();
	/**
	 * @var array available display modes
	 */
	protected $displayModes = array('horizontal', 'vertical');


	public function init()
	{
		if (!in_array($this->display, $this->displayModes))
			$this->display = $this->displayModes[0];
	}

	public function run()
	{
		echo $this->renderTabs();
	}

	protected function renderTabs()
	{
		return CHtml::tag(
			'div',
			$this->getOptions(),
			$this->renderTabsMenu() . $this->renderPanes()
		);
	}

	protected function renderTabsMenu()
	{
		$tabsMenu = '';
		foreach ($this->tabs as $tabId => $tabName)
			$tabsMenu .= $this->renderTab($tabId, $tabName);

		return CHtml::tag('ul', array('class' => 'tabs-menu'), $tabsMenu);
	}

	protected function renderTab($tabId, $tabName)
	{
		$tabClass = 'menu-item' . (($tabId == $this->activeTab) ? ' active-tab' : '');

		return CHtml::tag(
			'li',
			array('class' => $tabClass),
			CHtml::link(CHtml::tag('strong', array(), $tabName), '#' . $tabId)
		);
	}

	protected function renderPanes()
	{
		$panes = '';
		foreach ($this->panes as $tabId => $paneContent)
			$panes .= $this->renderPane($tabId, $paneContent);

		return $panes;
	}

	protected function renderPane($tabId, $paneContent)
	{
		$paneClass = 'tabs-pane' . (($tabId == $this->activeTab) ? ' active-pane' : '');

		return CHtml::tag(
			'div',
			array('id' => $tabId, 'class' => $paneClass),
			$paneContent
		);
	}

	protected function getOptions()
	{
		$options = $this->htmlOptions;

		$cssClass = $this->getTabsClass();
		if (isset($options['class']) && $options['class'] !== '')
			$options['class'] = $cssClass . ' ' . $options['class'];
		else
			$options['class'] = $cssClass;

		return $options;
	}

	protected function getTabsClass()
	{
		return 'aui-tabs ' . $this->display . '-tabs';
	}
} 