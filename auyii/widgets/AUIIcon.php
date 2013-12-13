<?php

class AUIIcon extends CWidget
{
	/**
	 * @var string icon name
	 */
	public $icon;
	/**
	 * @var bool whether this is a vector or bitmap icon
	 */
	public $vector = true;
	/**
	 * @var bool show large vector icon
	 */
	public $large = false;
	/**
	 * @var bool when set to true, $icon interpreted as a custom icon CSS class
	 */
	public $custom = false;
	/**
	 * @var array
	 */
	public $htmlOptions = array();

	/**
	 * Render icon
	 */
	public function run()
	{
		echo $this->renderIcon();
	}

	/**
	 * Return icon rendered according to settings specified
	 *
	 * @return string
	 */
	protected function renderIcon()
	{
		return CHtml::tag('span', $this->getOptions(), '');
	}

	/**
	 * Return HTML options
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		$options = $this->htmlOptions;
		$iconClass = 'aui-icon ' . $this->getIconClass() . ' ' . $this->getSizeClass();

		if (isset($options['class']) && $options['class'])
			$options['class'] = $iconClass . ' ' . $options['class'];
		else
			$options['class'] = $iconClass;

		return $options;
	}


	protected function getIconClass()
	{
		if ($this->custom)
			return $this->icon;

		return ($this->vector ? 'aui-iconfont-' : 'aui-icon-') . $this->icon;
	}

	protected function getSizeClass()
	{
		$class = '';
		if ($this->vector)
			$class = 'aui-icon-' . ($this->large ? 'large' : 'small');

		return $class;
	}
}