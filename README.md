AUYii
=====

AUYii is a collection of [Yii Framework 1.x](https://github.com/yiisoft/yii) widgets, which implements [Atlassian User Interface](https://docs.atlassian.com/aui/latest/) (AUI) components.


Installation
------------
- Copy AUYii in your application extensions directory
- Add AUYii as a component to your web application configuration:

```php
return array(
  // ...
  'components' => array(
    'aui' => array(
       'class' => 'application.extensions.auyii.AUYii',
    ),
  ),
);
```

- Add AUYii component to preload section of your web application config:

```php
return array(
  // ...
  'preload' => array('aui'),
);
```

Usage
-----

AUYii widgets may be used via application component shortcuts, e.g.:

```php
// output button immediately
Yii::app()->aui->button(array('label' => 'Apply'));

// capture output and return string value
$button = Yii::app()->aui->button(array('label' => 'Cancel'), true);
```
Also, widgets may be used with CController::widget() method:

```php
$controller->widget('aui.widgets.AUIButton', array('label' => 'Button Widget'));
```
