<?php

/**
 * AUI Toolbar widget
 *
 * See description at {@link https://docs.atlassian.com/aui/latest/docs/toolbar2.html}.
 */
class AUIToolbar extends CWidget
{
    /**
     * @var array toolbar groups
     *
     * Example:
     *
     *      array(
     *          array(
     *              'primary' => array(..) // group primary buttons
     *              'secondary' => array(..) // group secondary buttons
     *          ),
     *          array(
     *              'secondary' => array(..) // next group secondary buttons
     *          )
     *      )
     */
    public $groups = array();

    /**
     * @var array toolbar primary buttons
     *
     * Example:
     *
     *      array(
     *          // string values rendered "as-is"
     *          '<a href="/view" class="aui-button">View</a>',
     *          Yii::app()->aui->button(['label' => 'Edit'], true),
     *
     *          // array values passed as options to AUIButton widget
     *          array('label' => 'Delete')
     *      )
     */
    public $primary = array();
    /**
     * @var array toolbar secondary buttons
     */
    public $secondary = array();
    /**
     * @var array various HTML attributes for toolbar container tag
     */
    public $htmlOptions = array();

    /**
     * @var string default CSS class for toolbar container
     */
    protected $toolbarCSSClass = 'aui-toolbar2';
    /**
     * @var string default CSS class for toolbar inner container
     */
    protected $toolbarInnerCSSClass = 'aui-toolbar2-inner';
    /**
     * @var string default CSS class for toolbar group
     */
    protected $toolbarGroupCSSClass = 'aui-toolbar2-group';
    /**
     * @var string default CSS class for toolbar primary buttons container
     */
    protected $toolbarPrimaryCSSClass = 'aui-toolbar2-primary';
    /**
     * @var string default CSS class for toolbar secondary buttons container
     */
    protected $toolbarSecondaryCSSClass = 'aui-toolbar2-secondary';



    public function run()
    {
        echo $this->renderToolbar();
    }

    /**
     * Render toolbar
     *
     * @return string
     */
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

    /**
     * Render toolbar groups
     *
     * @return string
     */
    protected function renderGroups()
    {
        $groupsRendered = '';
        foreach ($this->groups as $group)
            $groupsRendered .= $this->renderGroup($group);

        return $groupsRendered;
    }

    /**
     * Render given toolbar $group
     *
     * @param $group
     * @return string
     */
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

    /**
     * Render primary/secondary button set
     *
     * @param $primary
     * @param $secondary
     * @return string
     */
    protected function renderButtonSet($primary, $secondary)
    {
        $buttonSet = '';

        if ($primary)
            $buttonSet .= $this->renderPrimary($primary);
        if ($secondary)
            $buttonSet .= $this->renderSecondary($secondary);

        return $buttonSet;
    }

    /**
     * Render primary buttons
     *
     * @param $buttons
     * @return string
     */
    protected function renderPrimary($buttons)
    {
        return CHtml::tag(
            'div',
            array('class' => $this->toolbarPrimaryCSSClass),
            $this->renderButtons($buttons)
        );
    }

    /**
     * Render secondary buttons
     *
     * @param $buttons
     * @return string
     */
    protected function renderSecondary($buttons)
    {
        return CHtml::tag(
            'div',
            array('class' => $this->toolbarSecondaryCSSClass),
            $this->renderButtons($buttons)
        );
    }

    /**
     * Render given $buttons
     *
     * @param $buttons
     * @return string
     */
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

    /**
     * Render button
     *
     * @param $button
     * @return mixed
     */
    protected function renderButton($button)
    {
        if (is_array($button))
            return $this->widget('aui.widgets.AUIButton', $button, true);
        else
            return $button;
    }

    /**
     * Get toolbar HTML options
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = $this->htmlOptions;
        $options['class'] = $this->toolbarCSSClass .
            (isset($options['class']) ? ' ' . $options['class'] : '');

		return $options;
    }
} 