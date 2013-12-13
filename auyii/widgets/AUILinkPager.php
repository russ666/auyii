<?php

class AUILinkPager extends CLinkPager
{
	public $firstPageLabel = 'First';
	public $prevPageLabel = 'Prev';
	public $nextPageLabel = 'Next';
	public $lastPageLabel = 'Last';

	public $firstPageCssClass = 'aui-nav-first';
	public $lastPageCssClass = 'aui-nav-last';
	public $previousPageCssClass = 'aui-nav-previous';
	public $nextPageCssClass = 'aui-nav-next';

	public $selectedPageCssClass = 'aui-nav-selected';
	public $hiddenPageCssClass = '';

	public $header = '';
	public $footer = '';

	public $pagerCssClass = 'aui-nav aui-nav-pagination';


	public function init()
	{
		if (!isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] = $this->pagerCssClass;
		else
			$this->htmlOptions['class'] .= ' ' . $this->pagerCssClass;
	}

	public function run()
	{
		$this->registerClientScript();

		$buttons = $this->createPageButtons();
		if (empty($buttons))
			return;

		echo $this->header;
		echo $this->renderPageButtons($buttons);
		echo $this->footer;
	}

	/**
	 * @param $buttons
	 * @return string
	 */
	protected function renderPageButtons($buttons)
	{
		return CHtml::tag('ol', $this->htmlOptions, implode("\n", $buttons));
	}

	/**
	 * @param string $label
	 * @param int $page
	 * @param string $class
	 * @param bool $hidden
	 * @param bool $selected
	 * @return string
	 */
	protected function createPageButton($label, $page, $class, $hidden, $selected)
	{
		if ($hidden || $selected)
			$class .= ' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);

		if ($selected)
			$pageLabel = $label;
		else {
			$linkOptions = $hidden ? array('aria-disabled' => 'true') : array();
			$pageLabel = CHtml::link($label, $this->createPageUrl($page), $linkOptions);
		}

		return CHtml::tag('li', array('class' => $class), $pageLabel);
	}
}