<?php

class AUIButton extends CWidget
{
	/**
	 * Button's id attribute. Leave empty for auto-generated value.
	 *
	 * @var string
	 */
	public $id;
	/**
	 * @var string button type.
	 *
	 * Can be: standard (default), primary, link, subtle.
	 */
	public $type;
	/**
	 * @var string button label text
	 */
	public $label;
	/**
	 * @var bool set to true to show pressed button
	 */
	public $pressed = false;
	/**
	 * @var bool set to true to show disabled button
	 */
	public $disabled = false;
	/**
	 * @var bool render button as input type="submit". Valid for standard and primary buttons only.
	 */
	public $submit = false;
	/**
	 * @var bool render compact button
	 */
	public $compact = false;
	/**
	 * @var string URL for link buttons
	 */
	public $url;
	/**
	 * @var string|array rendered {@link AUIIcon} widget or AUIIcon widget params array
	 */
	public $icon;
	/**
	 * @var string dropdown id for dropdown buttons. Button will be rendered as dropdown button, if $dropdown is set.
	 */
	public $dropdown;
	/**
	 * @var string JavaScript onClick handler
	 */
	public $onClick;
	/**
	 * @var array various HTML attributes for button
	 */
	public $htmlOptions;

	/**
	 * @var string default button type
	 */
	protected $defaultType = 'standard';
	/**
	 * @var array supported button types
	 */
	protected $buttonTypes = array(
		'standard',
		'primary',
		'link',
		'subtle'
	);


	public function init()
	{
		if (!$this->id)
			$this->id = $this->generateButtonId();
	}

	public function run()
	{
		echo $this->renderButton();
		$this->addJSHandlers();
	}

	protected function renderButton()
	{
		if (!$this->type || !in_array($this->type, $this->buttonTypes))
			$this->type = $this->defaultType;

		return $this->{'render' . ucfirst($this->type) . 'Button'}();
	}

	protected function renderStandardButton()
	{
		return $this->renderButtonTag($this->getLabel(), $this->getOptions());
	}

	protected function renderPrimaryButton()
	{
		$options = $this->getOptions();
		$options['class'] .= ' aui-button-primary';

		return $this->renderButtonTag($this->getLabel(), $options);
	}

	protected function renderLinkButton()
	{
		$options = $this->getOptions();
		$options['class'] .= ' aui-button-link';

		return $this->renderButtonTag($this->getLabel(), $options);
	}

	protected function renderSubtleButton()
	{
		$options = $this->getOptions();
		$options['class'] .= ' aui-button-subtle';

		return CHtml::htmlButton($this->getLabel(), $options);
	}

	protected function renderButtonTag($label, $options)
	{
		if ($this->url)
			return CHtml::link($label, $this->url, $options);

		return $this->submit ?
				CHtml::submitButton($label, $options) :
				CHtml::htmlButton($label, $options);
	}

	protected function getOptions()
	{
		$options = is_array($this->htmlOptions) ? $this->htmlOptions : array();

		$options['class'] = 'aui-button' . (isset($options['class']) ? ' '.$options['class'] : '');
		$options['id'] = $this->id;

		if ($this->pressed)
			$options['aria-pressed'] = 'true';

		if ($this->disabled)
			$options['aria-disabled'] = 'true';

		if ($this->compact)
			$options['class'] .= ' aui-button-compact';

		if ($this->dropdown)
			$options = $this->addDropDownOptions($options);

		return $options;
	}

	protected function addDropDownOptions($options)
	{
		$options['class'] .= ' aui-dropdown2-trigger';
		$options = array_merge(
			$options,
			[
				'aria-owns' => $this->dropdown,
				'aria-controls' => $this->dropdown,
				'aria-haspoup' => true
			]
		);

		return $options;
	}

	protected function getLabel()
	{
		$label = $this->label;
		if ($this->icon)
			$label = $this->renderIcon() . ' ' . $label;

		return $label;
	}

	protected function renderIcon()
	{
		return is_array($this->icon) ?
				$this->widget('aui.widgets.AUIIcon', $this->icon, true) :
				$this->icon;
	}

	protected function generateButtonId()
	{
		return uniqid('btn');
	}

	protected function addJSHandlers()
	{
		if ($this->onClick) {
			$handlersScript = 'AJS.$("#' .$this->id. '").on("click", ' .$this->onClick. ');';

			Yii::app()->clientScript->registerScript(
				__CLASS__ . '#' . $this->id,
				$handlersScript,
				CClientScript::POS_READY
			);
		}
	}
}