<?php

class AUIDropdown extends CWidget
{
	/**
	 * @var string dropdown unique id
	 */
	public $id;
	/**
	 * @var array dropdown menu items, when no sections used
	 */
	public $items;
	/**
	 * @var array dropdown menu sections
	 */
	public $sections;


	public function run()
	{
		echo $this->renderDropdown();
	}

	/**
	 * @return string
	 */
	protected function renderDropdown()
	{
		return CHtml::tag(
			'div',
			array(
				'id' => $this->id,
				'class' => 'aui-dropdown2 aui-style-default',
			),
			$this->renderDropdownContent()
		);
	}

	/**
	 * Render content of dropdown container
	 *
	 * @return string
	 */
	protected function renderDropdownContent()
	{
		return ($this->sections && is_array($this->sections)) ?
				$this->renderSections() :
				$this->renderItems($this->items);
	}


	/**
	 * Render dropdown sections
	 *
	 * @return string
	 */
	protected function renderSections()
	{
		$sections = '';
		foreach ($this->sections as $section)
			$sections .= $this->renderSection($section);

		return $sections;
	}

	/**
	 * Render dropdwon section
	 *
	 * @param $section
	 * @return string
	 */
	protected function renderSection($section)
	{
		if (!isset($section['items']) || !$section['items'])
			return '';

		$title = isset($section['title']) ? $section['title'] : false;

		return CHtml::tag(
			'div',
			array('class' => 'aui-dropdown2-section'),
			$this->renderSectionTitle($title) .	$this->renderItems($section['items'])
		);
	}

	/**
	 * Render dropdown section title
	 *
	 * @param $title
	 * @return string
	 */
	protected function renderSectionTitle($title)
	{
		return $title ? CHtml::tag('strong',array(),$title) : '';
	}

	/**
	 * Render dropdown menu items
	 *
	 * @param $items
	 * @return string
	 */
	protected function renderItems($items)
	{
		$listOptions = array();
		$truncateItems = isset($items['truncate']) ? $items['truncate'] : false;
		if ($truncateItems)
			$listOptions['class'] = 'aui-list-truncate';

		$renderedItems = '';
		foreach ($items as $item)
			$renderedItems .= $this->renderItem($item);

		return CHtml::tag('ul', $listOptions, $renderedItems);
	}

	/**
	 * Render dropdown menu item
	 *
	 * @param $item
	 * @return string
	 */
	protected function renderItem($item)
	{
		if (!is_array($item))
			return '';

		return CHtml::tag('li', array(), $this->renderItemLink($item));
	}

	/**
	 * Render menu item link
	 *
	 * @param $item
	 * @return string
	 */
	protected function renderItemLink($item)
	{
		if (isset($item['raw']))
			return CHtml::tag('a', $this->getItemOptions($item), $item['raw']);

		$title = isset($item['title']) ? $item['title'] : '';
		$url = isset($item['url']) ? $item['url'] : '';

		if (isset($item['icon']))
			$title = $item['icon'] . ' ' . $title;

		return CHtml::link($title, $url, $this->getItemOptions($item));
	}

	/**
	 * Get menu item render options
	 *
	 * @param $item
	 * @return array
	 */
	protected function getItemOptions($item)
	{
		$options = $this->getItemTagOptions($item);
		$class = array();

		if (isset($item['type']))
			switch ($item['type']) {
				case 'radio':
					$class[] = 'aui-dropdown2-radio';
					break;
				case 'checkbox':
					$class[] = 'aui-dropdown2-checkbox';
					break;
				case 'icon':
					$class[] = 'aui-icon-container';
			}

		if ($this->itemOptionSet($item, 'disabled'))
			$class[] = 'disabled';

		if ($this->itemOptionSet($item, 'checked'))
			$class[] = 'checked';

		if ($this->itemOptionSet($item, 'interactive'))
			$class[] = 'interactive';

		if ($class) {
			if (isset($options['class']))
				$class[] = $options['class'];

			$options['class'] = implode(' ', $class);
		}

		return $options;
	}

	/**
	 * Check if specified item option is set
	 *
	 * @param $item
	 * @param $option
	 * @return bool
	 */
	protected function itemOptionSet($item, $option)
	{
		return isset($item[$option]) && $item[$option];
	}

	/**
	 * Get additional item options which will be rendered as HTML tag attributes
	 *
	 * @param $item
	 * @return array
	 */
	protected function getItemTagOptions($item)
	{
		return array_diff_key($item, array_flip(array('title', 'url', 'type', 'disabled', 'checked')));
	}
}