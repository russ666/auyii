<?php

class AUIAppHeader extends CWidget
{
	/**
	 * @var string logo HTML
	 */
	public $logo;

	/**
	 * @var array Left-aligned menu items
	 */
	public $leftItems = array();
	/**
	 * @var array Right-aligned menu items
	 */
	public $rightItems = array();
	/**
	 * @var array|string|bool search box HTML code or search settings array
	 */
	public $search = array();
	/**
	 * @var array various HTML attributes for "header" tag
	 */
	public $htmlOptions = array();


	public function run()
	{
		echo $this->renderAppHeader();
	}

	/**
	 * Construct and render application header
	 *
	 * @return string
	 */
	protected function renderAppHeader()
	{
		$headerOptions = array_merge(
			array('role' => 'banner'),
			$this->htmlOptions
		);

		return CHtml::tag(
			'header',
			$headerOptions,
			$this->renderNavigation()
		);
	}

	/**
	 * Render navigation
	 *
	 * @return string
	 */
	protected function renderNavigation()
	{
		$navOptions = array(
			'class' => 'aui-header aui-dropdown2-trigger-group',
			'role' => 'navigation'
		);

		return CHtml::tag(
			'nav',
			$navOptions,
			$this->renderLeftNavigation() . $this->renderRightNavigation()
		);
	}

	/**
	 * Render primary header part
	 *
	 * @return string
	 */
	protected function renderLeftNavigation()
	{
		return CHtml::tag(
			'div',
			array('class' => 'aui-header-primary'),
			$this->renderLogo() . $this->renderLeftItems()
		);
	}

	/**
	 * Render secondary header part
	 *
	 * @return string
	 */
	protected function renderRightNavigation()
	{
		return CHtml::tag(
			'div',
			array('class' => 'aui-header-secondary'),
			$this->renderRightItems()
		);
	}

	/**
	 * Render application logo
	 *
	 * @return string
	 */
	protected function renderLogo()
	{
		$logoOptions = array(
			'id' => 'logo',
			'class' => 'aui-header-logo aui-header-logo-aui'
		);

		return $this->logo ? CHtml::tag('h1', $logoOptions, $this->logo) : '';
	}

	/**
	 * Render left-aligned navigation items
	 *
	 * @return string
	 */
	protected function renderLeftItems()
	{
		return $this->renderNavItems($this->leftItems);
	}

	/**
	 * Render right-aligned navigation items
	 *
	 * @return string
	 */
	protected function renderRightItems()
	{
		return $this->renderNavItems(array_merge(
			array($this->renderSearch()),
			$this->rightItems
		));
	}

	/**
	 * Render navigation items
	 *
	 * @param $items
	 * @return string
	 */
	protected function renderNavItems($items)
	{
		if (!$items)
			return '';

		$navItems = '';
		foreach ($items as $item)
			$navItems .= $this->renderNavItem($item);

		return CHtml::tag('ul', [ 'class' => 'aui-nav' ], $navItems);
	}

	/**
	 * Render navigation item
	 *
	 * @param $item
	 * @return string
	 */
	protected function renderNavItem($item)
	{
		$itemRendered = $item;

		if (is_array($item)) {
			if (!isset($item['id']))
				$item['id'] = $this->generateItemId();

			$itemRendered = $this->renderNavItemLink($item) . $this->renderNavItemDropdown($item);
		}

		return CHtml::tag('li', [], $itemRendered);
	}

	/**
	 * Render navigation item as link
	 *
	 * @param $item
	 * @return string
	 */
	protected function renderNavItemLink($item)
	{
		$options = [];
		$url = isset($item['url']) ? $item['url'] : '';
		$label = isset($item['label']) ? $item['label'] : '';

		if (isset($item['items']) && $item['items']) {
			$options = array_merge(
				$options,
				[
					'class' => 'aui-dropdown2-trigger',
					'aria-owns' => $item['id'],
					'aria-haspopup' => true,
					'aria-controls' => $item['id'],
				]
			);
		}

		return CHtml::link($label, $url, $options);
	}

	/**
	 * Render navigation item as dropdown menu
	 *
	 * @param $item
	 * @return mixed|string
	 */
	protected function renderNavItemDropdown($item)
	{
		if (!isset($item['items']))
			return '';

		$items = $item['items'];
		$items['id'] = $item['id'];

		return $this->widget('aui.widgets.AUIDropdown', $items, true);
	}

	/**
	 * Render search item
	 *
	 * @return array|void
	 */
	protected function renderSearch()
	{
		if ($this->search && !is_string($this->search))
			return $this->renderDefaultSearch();

		return $this->search;
	}

	/**
	 * Render default quick search field
	 */
	protected function renderDefaultSearch()
	{
		$searchSettings = $this->getSearchSettings();
		$searchOptions = array(
			'class' => 'aui-quicksearch',
			'method' => $searchSettings['method'],
			'action' => $searchSettings['action'],
		);

		$labelOptions = array(
			'for' => $searchSettings['id'],
			'class' => 'assistive',
		);

		$inputOptions = array(
			'id' => $searchSettings['id'],
			'class' => 'search',
			'placeholder' => $searchSettings['label']
		);

		return CHtml::tag(
			'form',
			$searchOptions,
			CHtml::tag('label', $labelOptions, $searchSettings['label']).
			CHtml::textField($searchSettings['name'], $searchSettings['query'], $inputOptions)
		);
	}

	/**
	 * Prepare searchbox settings array
	 *
	 * @return array
	 */
	protected function getSearchSettings()
	{
		return array(
			'id' => isset($this->search['id']) ? $this->search['id'] : $this->generateItemId(),
			'name' => isset($this->search['name']) ? $this->search['name'] : 'q',
			'method' => isset($this->search['method']) ? $this->search['method'] : 'post',
			'action' => isset($this->search['action']) ? $this->search['action'] : '',
			'label' => isset($this->search['label']) ? $this->search['label'] : 'Search',
			'query' => isset($this->search['query']) ? $this->search['query'] : '',
		);
	}

	/**
	 * Generate unique item id
	 *
	 * @return string
	 */
	protected function generateItemId()
	{
		return uniqid('nav');
	}
}