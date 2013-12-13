<?php

Yii::import('zii.widgets.grid.CGridView');
Yii::import('aui.widgets.AUILinkPager');
Yii::import('aui.widgets.AUIActionsColumn');

class AUIGridView extends CGridView
{
	public $enableSorting = false;

	public $itemsCssClass = 'aui auyii-grid-view';

	public $pager = array('class' => 'AUILinkPager');
	public $pagerCssClass = 'auyii-link-pager';


	public function init()
	{
		if ($this->enableSorting)
			$this->itemsCssClass .= ' auyii-grid-view-sortable';

		parent::init();
	}
}