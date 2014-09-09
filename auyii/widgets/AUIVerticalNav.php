<?php

class AUIVerticalNav extends CWidget
{
	/**
	 * @var array navigation items
	 */
	public $items;
	/**
	 * @var array
	 */
	public $htmlOptions = array();


	public function run()
	{
		echo $this->renderVerticalNav();
	}

	/**
	 * Render vertical navigation
	 */
	public function renderVerticalNav()
	{
		$navOptions = CMap::mergeArray(
			array('class' => 'aui-navgroup aui-navgroup-vertical'),
			$this->htmlOptions
		);

		return CHtml::tag(
			'nav',
			$navOptions,
			CHtml::tag('div', array('class' => 'aui-navgroup-inner'), $this->renderNavSections())
		);
	}

	/**
	 * Render navigation sections & headers
	 *
	 * @return string
	 */
	public function renderNavSections()
	{
		$navItems = '';
		foreach ($this->items as $header => $sectionItems)
			$navItems .= $this->renderNavSection($header, $sectionItems);

		return $navItems;
	}

	/**
	 * Render navigation section
	 *
	 * @param $header
	 * @param $sectionItems
	 * @return string
	 */
	public function renderNavSection($header, $sectionItems)
	{
		$navSection = '';

		if ($header && is_string($header))
			$navSection .= CHtml::tag(
				'div',
				array('class' => 'aui-nav-heading'),
				CHtml::tag('strong', array(), $header)
			);

		$navSection .= CHtml::tag(
			'ul',
			array('class' => 'aui-nav'),
			$this->renderNavItems($sectionItems)
		);

		return $navSection;
	}

	/**
	 * Render navigation section items
	 *
	 * @param $items
	 * @return string
	 */
	public function renderNavItems($items)
	{
		$navItems = '';
		foreach ($items as $item)
			$navItems .= $this->renderNavItem($item);

		return $navItems;
	}

	/**
	 * Render navigation item
	 *
	 * @param $item
	 * @return bool|string
	 */
	public function renderNavItem($item)
	{
		if (!$this->validateNavItem($item))
			return false;

		$itemOptions = $this->getNavItemOptions($item);
        $itemLinkOptions = $this->getNavItemLinkOptions($item);

        $activeClass = 'aui-nav-selected';

		if ($this->isItemActive($item))
            if (isset($itemOptions['class']) && is_string($itemOptions['class']))
                $itemOptions['class'] .= ' ' . $activeClass;
            else
                $itemOptions['class'] = $activeClass;

		return CHtml::tag(
			'li',
			$itemOptions,
			CHtml::link($item['label'], $item['url'], $itemLinkOptions)
		);
	}

	/**
	 * Check if navigation item structure is valid
	 *
	 * @param $item
	 * @return bool
	 */
	protected function validateNavItem($item)
	{
		return isset($item['label']) && isset($item['url']);
	}

    /**
     * Fetch navigation item html options
     *
     * @param $item
     * @return array
     */
    protected function getNavItemOptions($item)
    {
        $result = array();

        if (isset($item['htmlOptions']) && is_array($item['htmlOptions']))
            $result = $item['htmlOptions'];

        return $result;
    }

    /**
     * Fetch navigation item link html options
     *
     * @param $item
     * @return array
     */
    protected function getNavItemLinkOptions($item)
    {
        $result = array();

        if (isset($item['linkHtmlOptions']) && is_array($item['linkHtmlOptions']))
            $result = $item['linkHtmlOptions'];

        return $result;
    }

	/**
	 * Check if navigation item is active
	 *
	 * @param $item
	 * @return bool
	 */
	protected function isItemActive($item)
	{
		return isset($item['active']) && $item['active'];
	}
}