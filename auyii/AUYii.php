<?php

class AUYii extends CApplicationComponent
{
	/**
	 * @var string path alias for auyii extension directory
	 */
	protected $pathAlias = 'aui';
	/**
	 * @var array widget short name => class name map
	 */
	protected $widgetShortcuts = array(
		'appHeader' => 'AUIAppHeader',
		'avatar' => 'AUIAvatar',
		'badge' => 'AUIBadge',
		'button' => 'AUIButton',
		'dropDown' => 'AUIDropdown',
		'gridView' => 'AUIGridView',
        'horizontalNav' => 'AUIHorizontalNav',
		'icon' => 'AUIIcon',
		'label' => 'AUILabel',
		'lozenge' => 'AUILozenge',
		'pageHeader' => 'AUIPageHeader',
		'tabs' => 'AUITabs',
		'verticalNav' => 'AUIVerticalNav'
	);

	public $components;

	public function init()
	{
		$this->registerPathAlias();
		$this->registerAssets();
	}

	public function __call($method, $args)
	{
		if (isset($this->widgetShortcuts[$method]))
			return call_user_func_array(
				array($this, 'renderWidget'),
				array_merge(
					array($this->widgetShortcuts[$method]),
					$args
				)
			);
	}


	protected function renderWidget($className, $properties = array(), $captureOutput = false)
	{
		if (!Yii::app()->controller)
			throw new RuntimeException('Application has no active controller');

		return Yii::app()->controller->widget($this->getWidgetPathAlias($className), $properties, $captureOutput);
	}

	protected function getWidgetPathAlias($className)
	{
		return $this->pathAlias . '.widgets.' . $className;
	}

	protected function registerPathAlias()
	{
		if (!Yii::getPathOfAlias($this->pathAlias))
			Yii::setPathOfAlias($this->pathAlias, dirname(__FILE__));
	}

	protected function registerAssets()
	{
		$this->registerCSS();
		$this->registerScripts();
		$this->registerFrameworkAssets();
	}

	protected function registerFrameworkAssets()
	{
		Yii::app()->clientScript->registerCoreScript('jquery')
								->registerCoreScript('jquery.ui');
	}

	protected function registerCSS()
	{
		$cssUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/aui/aui/css');
		$assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets/css');

		Yii::app()->clientScript->registerCssFile($cssUrl . '/aui-all.css');
		Yii::app()->clientScript->registerCssFile($assetsUrl . '/auyii.min.css');
	}

	protected function registerScripts()
	{
		$jsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/aui/aui/js');

		Yii::app()->clientScript->registerScriptFile($jsUrl . '/aui.js');
		Yii::app()->clientScript->registerScriptFile($jsUrl . '/aui-soy.js');

		//experimental features
		Yii::app()->clientScript->registerScriptFile($jsUrl . '/aui-experimental.js');
	}
}