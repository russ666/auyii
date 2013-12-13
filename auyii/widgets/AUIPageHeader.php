<?php

class AUIPageHeader extends CWidget
{
	/**
	 * @var string header image HTML
	 */
	public $image;

	/**
	 * @var array list of page titles and their URLs
	 *
	 * Example:
	 * array(
			'About' => '/about',
	 *      'Team' => '/team',
	 *      'Joe' => '' (no link rendered for empty URL)
	 * )
	 */
	public $breadcrumbs;

	/**
	 * @var string Page title
	 */
	public $title;

	/**
	 * @var int Header size, 1 for h1, 2 for h2 etc.
	 */
	public $size;

	/**
	 * @var array array of action buttons HTML, see {@link AUIButton}
	 */
	public $actions;

	public $htmlOptions = array();


	public function run()
	{
		echo $this->renderPageHeader();
	}

	/**
	 * Construct and render page header
	 *
	 * @return string
	 */
	public function renderPageHeader()
	{
		$headerOptions = CMap::mergeArray(
			array('class' => 'aui-page-header'),
			$this->htmlOptions
		);

		return CHtml::tag(
			'header',
			$headerOptions,
			CHtml::tag('div', array('class' => 'aui-page-header-inner'),
				$this->renderImage() . $this->renderMainHeader() . $this->renderActions()
			)
		);
	}

	/**
	 * Render page header image/avatar
	 *
	 * @return string
	 */
	public function renderImage()
	{
		if (!$this->image)
			return '';

		return CHtml::tag(
			'div',
			array('class' => 'aui-page-header-image'),
			$this->image
		);
	}

	/**
	 * Render page title and breadcrumbs
	 *
	 * @return string
	 */
	public function renderMainHeader()
	{
		return CHtml::tag(
			'div',
			array('class' => 'aui-page-header-main'),
			$this->renderBreadcrumbs() . $this->renderHeaderTitle()
		);
	}

	/**
	 * Render header title of given size
	 *
	 * @return string
	 */
	public function renderHeaderTitle()
	{
		$headerSize = $this->size && ctype_digit((string)$this->size) ? $this->size : 1;
		return CHtml::tag('h' . $headerSize, array(), $this->title);
	}

	/**
	 * Render breadcrumbs
	 *
	 * @return string
	 */
	public function renderBreadcrumbs()
	{
		if (!is_array($this->breadcrumbs))
			return '';

		$breadcrumbs = '';
		foreach ($this->breadcrumbs as $title => $url)
			$breadcrumbs .= $this->renderBreadcrumbLink($title, $url);

		return CHtml::tag(
			'ol',
			array('class' => 'aui-nav aui-nav-breadcrumbs'),
			$breadcrumbs
		);
	}

	/**
	 * Render breadcrumbs item
	 *
	 * @param string $title
	 * @param string $url
	 * @return string
	 */
	public function renderBreadcrumbLink($title, $url)
	{
		$breadcrumbOptions = array();

		if ($url) {
			$breadcrumb = CHtml::link($title, $url);
		} else {
			$breadcrumb = $title;
			$breadcrumbOptions['class'] = 'aui-nav-selected';
		}

		return CHtml::tag('li', $breadcrumbOptions, $breadcrumb);
	}

	/**
	 * Render action buttons
	 *
	 * @return string
	 */
	public function renderActions()
	{
		if (!$this->actions || !is_array($this->actions))
			return '';

		return CHtml::tag(
			'div',
			array('class' => 'aui-page-header-actions'),
			CHtml::tag(
				'div',
				array('class' => 'aui-buttons'),
				implode('', $this->actions)
			)
		);
	}
}