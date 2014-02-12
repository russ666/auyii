<?php

class AUIActiveForm extends CActiveForm
{
	/**
	 * @var string AUI Form layout. e.g. long-label, top-label, unsectioned.
	 * Leave empty for default layout.
	 * See {@link https://docs.atlassian.com/aui/latest/docs/forms.html}
	 */
	public $layout;
	/**
	 * @var string Required mark which is used in labelEx method
	 */
	public $afterRequiredLabel = '<span class="aui-icon icon-required">(required)</span>';
	/**
	 * @var string Default class for buttons container
	 */
	public $buttonsContainerClass = 'buttons-container';
	/**
	 * @var string Default class for buttons block
	 */
	public $buttonsClass = 'buttons';
	/**
	 * @var string Default class for field group DIV
	 */
	public $fieldGroupClass = 'field-group';
	/**
	 * @var string Default class for description DIV
	 */
	public $descriptionClass = 'description';
	/**
	 * @var string Default CSS class for error summary
	 */
	public $errorSummaryCssClass = 'aui-message error';


	public function __call($name, $parameters)
	{
		if ($inputMethod = $this->customWidthTextInput($name))
			return $this->callTextInputMethod(
				$inputMethod['size'],
				$inputMethod['method'],
				$parameters
			);

		return parent::__call($name, $parameters);
	}

	public function init()
	{
		$this->setClassOption(
			'aui' . ($this->layout ? ' '.$this->layout : ''),
			$this->htmlOptions
		);

		if (!$this->errorMessageCssClass)
			$this->errorMessageCssClass = 'error';

		parent::init();
	}

	/**
	 * @param CModel $model
	 * @param string $attribute
	 * @param array $htmlOptions
	 * @return string
	 */
	public function checkBox($model, $attribute, $htmlOptions=array())
	{
		$this->setClassOption('checkbox', $htmlOptions);

		return CHtml::tag(
			'div', array('class' => 'checkbox'),
			parent::checkBox($model, $attribute, $htmlOptions) . $this->label($model, $attribute)
		);
	}

	/**
	 * @param CModel $model
	 * @param string $attribute
	 * @param array $data
	 * @param array $htmlOptions
	 * @return string
	 */
	public function dropDownList($model, $attribute, $data, $htmlOptions=array())
	{
		$this->setClassOption('select', $htmlOptions);
		return parent::dropDownList($model, $attribute, $data, $htmlOptions);
	}

	/**
	 * @param mixed $models
	 * @param null $header
	 * @param null $footer
	 * @param array $htmlOptions
	 * @return string
	 */
	public function errorSummary($models, $header = null, $footer = null, $htmlOptions = array())
	{
		$this->setClassOption('aui-message error', $htmlOptions);

		$headerMessage = is_null($header) ? Yii::t('yii','Please fix the following input errors:') : $header;
		$header = $this->getErrorSummaryHeader($headerMessage);

		return parent::errorSummary($models, $header, $footer, $htmlOptions);
	}

	/**
	 * @param CModel $model
	 * @param string $attribute
	 * @param array $htmlOptions
	 * @return string
	 */
	public function labelEx($model, $attribute, $htmlOptions=array())
	{
		$afterRequiredLabel = CHtml::$afterRequiredLabel;
		CHtml::$afterRequiredLabel = $this->afterRequiredLabel;

		$labelRendered = parent::labelEx($model, $attribute, $htmlOptions);

		CHtml::$afterRequiredLabel = $afterRequiredLabel;

		return $labelRendered;
	}

	/**
	 * @param CModel $model
	 * @param string $attribute
	 * @param array $htmlOptions
	 * @return string
	 */
	public function textField($model, $attribute, $htmlOptions=array())
	{
		$this->setClassOption('text', $htmlOptions);
		return parent::textField($model, $attribute, $htmlOptions);
	}

	/**
	 * @param CModel $model
	 * @param string $attribute
	 * @param array $htmlOptions
	 * @return string
	 */
	public function textArea($model, $attribute, $htmlOptions=array())
	{
		$this->setClassOption('textarea', $htmlOptions);
		return parent::textArea($model, $attribute, $htmlOptions);
	}

	/**
	 * @param array $buttons
	 * @param array $htmlOptions
	 * @return string
	 */
	public function buttons($buttons, $htmlOptions=array())
	{
		$this->setClassOption($this->buttonsContainerClass, $htmlOptions);
		return CHtml::tag(
			'div',
			$htmlOptions,
			CHtml::tag('div', array('class' => 'buttons'), implode($buttons))
		);
	}

	/**
	 * @param $fields
	 * @param array $htmlOptions
	 * @return string
	 */
	public function fieldGroup($fields, $htmlOptions=array())
	{
		$this->setClassOption($this->fieldGroupClass, $htmlOptions);
		return CHtml::tag('div', $htmlOptions, implode('', $fields));
	}

	/**
	 * @param $fields
	 * @param string $legend
	 * @param array $htmlOptions
	 * @return string
	 */
	public function fieldSet($fields, $legend='', $htmlOptions=array())
	{
		if ($legend)
			$legend = CHtml::tag('legend', array(), CHtml::tag('span', array(), $legend));

		return CHtml::tag(
			'fieldset', $htmlOptions,
			$legend . implode('', $fields)
		);
	}

	/**
	 * @param $description
	 * @param array $htmlOptions
	 * @return string
	 */
	public function description($description, $htmlOptions=array())
	{
		$this->setClassOption($this->descriptionClass, $htmlOptions);
		return CHtml::tag('div', $htmlOptions, $description);
	}

	/**
	 * @param $class
	 * @param $options
	 */
	protected function setClassOption($class, &$options)
	{
		if (!is_array($options))
			$options = array();

		if (isset($options['class']) && $options['class'] !== '')
			$options['class'] .= ' ' . $class;
		else
			$options['class'] = $class;
	}

	/**
	 * Call a text-input render method with custom width
	 *
	 * @param $size
	 * @param $method
	 * @param $parameters
	 * @return mixed
	 */
	protected function callTextInputMethod($size, $method, $parameters)
	{
		$sizeClass = $this->getSizeClass($size);

		$customMethod = new ReflectionMethod($this, $method);
		$methodParams = $customMethod->getParameters();

		//find 'htmlOptions' position in arguments list
		foreach ($methodParams as $param)
			if ($param->getName() == 'htmlOptions') {
				$optionsPosition = $param->getPosition();
				break;
			}

		if (isset($optionsPosition)) {
			if (!isset($parameters[$optionsPosition]))
				$parameters[$optionsPosition] = array();

			$this->setClassOption($sizeClass, $parameters[$optionsPosition]);
		}

		return call_user_func_array(array($this, $method), $parameters);
	}

	/**
	 * Return CSS class for custom width modifier
	 *
	 * @param $size
	 * @return string
	 */
	protected function getSizeClass($size)
	{
		switch ($size) {
			case 'short':
			case 'medium':
			case 'long': return $size . '-field';
			case 'full': return 'full-width-field';
			default: return '';
		}
	}

	/**
	 * Check if method called renders text input field and it's name contains width modifier
	 *
	 * @param $methodName
	 * @return array|bool
	 */
	protected function customWidthTextInput($methodName)
	{
		if (!preg_match('/(?P<size>[short|medium|long|full]+)(?P<method>.+)/', $methodName, $matches))
			return false;

		$method = lcfirst($matches['method']);
		if (!in_array($method, $this->textInputMethods()))
			return false;

		return array('size' => $matches['size'], 'method' => $method);
	}

	/**
	 * Return the list of method names which may be used to render text input controls
	 *
	 * @return array
	 */
	protected function textInputMethods()
	{
		return array(
			'emailField', 'numberField', 'passwordField', 'rangeField',
			'searchField', 'telField', 'textField', 'timeField', 'urlField', 'textArea'
		);
	}

	/**
	 * Return error summary header formatted according to ADG
	 *
	 * @param $headerMessage
	 * @return string
	 */
	protected function getErrorSummaryHeader($headerMessage)
	{
		return CHtml::tag('p', array('class' => 'title'),
			$this->widget('aui.widgets.AUIIcon', array('icon' => 'error'), true) .
			CHtml::tag('strong', array(), $headerMessage)
		);
	}
}