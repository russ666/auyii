<?php

class AUIAvatar extends CWidget
{
	/**
	 * @var string avatar size
	 *
	 * Supported values:
	 *      xsmall      => 16px
	 *      small       => 24px
	 *      medium      => 32px
	 *      large       => 48px
	 *      xlarge      => 64px (project avatars only)
	 *      xxlarge     => 96px
	 *      xxxlarge    => 128px (project avatars only)
	 */
	public $size;
	/**
	 * @var string avatar URL
	 */
	public $src;
	/**
	 * @var bool whether this is a project avatar
	 */
	public $project = false;


	public function run()
	{
		echo $this->renderAvatar();
	}

	protected function renderAvatar()
	{
		return CHtml::tag('span', $this->getOptions(),
			CHtml::tag('span', array('class' => 'aui-avatar-inner'), CHtml::image($this->src))
		);
	}

	protected function getOptions()
	{
		return array(
			'class' => 'aui-avatar aui-avatar-' . $this->size . ($this->project ? ' aui-avatar-project' : '')
		);
	}
}