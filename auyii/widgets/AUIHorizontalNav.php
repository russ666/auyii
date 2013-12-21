<?php

class AUIHorizontalNav extends CWidget
{
	/**
	 * @var array primary navigation items
	 */
	public $items;
    /**
     * @var array secondary navigation items
     */
    public $secondaryItems;
	/**
	 * @var array
	 */
	public $htmlOptions = array();


	public function run()
	{
		echo $this->renderHorizontalNav();
	}

	/**
	 * Render vertical navigation
	 */
	public function renderHorizontalNav()
	{
		$navOptions = CMap::mergeArray(
			array('class' => 'aui-navgroup aui-navgroup-horizontal'),
			$this->htmlOptions
		);

		return CHtml::tag(
			'nav',
			$navOptions,
			CHtml::tag('div', array('class' => 'aui-navgroup-inner'), $this->renderNavSections())
		);
	}

	/**
	 * Render navigation sections
	 *
	 * @return string
	 */
	public function renderNavSections()
	{
        $primaryNavSection = '';
        if ($this->items)
            $primaryNavSection = CHtml::tag(
                'div',
                array('class' => 'aui-navgroup-primary'),
                $this->renderNavSection($this->items)
            );

        $secondaryNavSection = '';
        if ($this->secondaryItems)
            $secondaryNavSection = CHtml::tag(
                'div',
                array('class' => 'aui-navgroup-secondary'),
                $this->renderNavSection($this->secondaryItems)
            );

		return $primaryNavSection . $secondaryNavSection;
	}

	/**
	 * Render navigation section
	 *
	 * @param $sectionItems
	 * @return string
	 */
	public function renderNavSection($sectionItems)
	{
		$result = CHtml::tag(
			'ul',
			array('class' => 'aui-nav'),
			$this->renderNavItems($sectionItems)
		);

		return $result;
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

		$itemOptions = array();
		if ($this->isItemActive($item))
			$itemOptions['class'] = 'aui-nav-selected';

		return CHtml::tag(
			'li',
			$itemOptions,
			CHtml::link($item['label'], $item['url'])
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