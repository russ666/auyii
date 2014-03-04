<?php

class AUIToolbar extends CWidget
{
    /**
     * @var array
     */
    public $groups = array();

    /**
     * @var array
     */
    public $primary = array();
    /**
     * @var array
     */
    public $secondary = array();

    public $htmlOptions = array();

    protected $toolbarCSSClass = 'aui-toolbar2';
    protected $toolbarInnerCSSClass = 'aui-toolbar2-inner';
    protected $toolbarGroupCSSClass = 'aui-toolbar2-group';
    protected $toolbarPrimaryCSSClass = 'aui-toolbar2-primary';
    protected $toolbarSecondaryCSSClass = 'aui-toolbar2-secondary';


    public function run()
    {
        echo $this->renderToolbar();
    }

    protected function renderToolbar()
    {
        return CHtml::tag(
            'div',
            $this->getOptions(),
            CHtml::tag(
                'div',
                array('class' => $this->toolbarInnerCSSClass),
                $this->groups ?
                    $this->renderGroups() :
                    $this->renderButtonSet($this->primary, $this->secondary)
            )
        );
    }

    protected function renderGroups()
    {
        $groupsRendered = '';
        foreach ($this->groups as $group)
            $groupsRendered .= $this->renderGroup($group);

        return $groupsRendered;
    }

    protected function renderGroup($group)
    {
        if (!is_array($group))
            return '';

        $primary = isset($group['primary']) ? $group['primary'] : '';
        $secondary = isset($group['secondary']) ? $group['secondary'] : '';

        return CHtml::tag(
            'div',
            array('class' => $this->toolbarGroupCSSClass),
            $this->renderButtonSet($primary, $secondary)
        );
    }

    protected function renderButtonSet($primary, $secondary)
    {
        $buttonSet = '';

        if ($primary)
            $buttonSet .= $this->renderPrimary($primary);
        if ($secondary)
            $buttonSet .= $this->renderSecondary($secondary);

        return $buttonSet;
    }

    protected function renderPrimary($buttons)
    {
        return CHtml::tag(
            'div',
            array('class' => $this->toolbarPrimaryCSSClass),
            $this->renderButtons($buttons)
        );
    }

    protected function renderSecondary($buttons)
    {
        return CHtml::tag(
            'div',
            array('class' => $this->toolbarSecondaryCSSClass),
            $this->renderButtons($buttons)
        );
    }

    protected function renderButtons($buttons)
    {
        if (is_array($buttons)) {
            $buttonsRendered = '';
            foreach ($buttons as $button)
                $buttonsRendered .= $this->renderButton($button);

            return $buttonsRendered;
        } else
            return $buttons;
    }

    protected function renderButton($button)
    {
        if (is_array($button))
            return $this->widget('aui.widgets.AUIButton', $button, true);
        else
            return $button;
    }

    protected function getOptions()
    {
        $options = $this->htmlOptions;
        $options['class'] = $this->toolbarCSSClass .
            (isset($options['class']) ? ' ' . $options['class'] : '');

		return $options;
    }
} 