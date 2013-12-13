<?php

class AUIBadge extends CWidget
{
	/**
	 * @var string badge text
	 */
	public $text;


	public function run()
	{
		echo $this->renderBadge();
	}

	protected function renderBadge()
	{
		return CHtml::tag(
			'span',
			array('class' => 'aui-badge'),
			$this->text
		);
	}
}