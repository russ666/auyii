<?php

class AUILozenge extends CWidget
{
	/**
	 * @var string lozenge text
	 */
	public $title;
	/**
	 * @var string lozenge type. One of [ success, error, current, complete, moved ]
	 */
	public $type;
	/**
	 * @var bool render subtle lozenge
	 */
	public $subtle;


	public function run()
	{
		echo $this->renderLozenge();
	}

	public function renderLozenge()
	{
		return CHtml::tag(
			'span',
			$this->getRenderOptions(),
			$this->title
		);
	}

	protected function getRenderOptions()
	{
		$class = 'aui-lozenge';

		if ($this->subtle)
			$class .= ' aui-lozenge-subtle';

		if ($this->type)
			$class .= ' aui-lozenge-' . $this->type;

		return array(
			'class' => $class
		);
	}
}