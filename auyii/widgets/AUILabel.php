<?php

class AUILabel extends CWidget
{
	/**
	 * @var string label text
	 */
	public $label;

	public function run()
	{
		echo $this->renderLabel();
	}

	protected function renderLabel()
	{
		return CHtml::tag('span', array('class' => 'aui-label'), $this->label);
	}
}