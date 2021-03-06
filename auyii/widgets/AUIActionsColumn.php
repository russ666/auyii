<?php

class AUIActionsColumn extends CDataColumn
{
	/**
	 * @var string default CSS class for actions column
	 */
	public $actionsColumnCSSClass = 'auyii-actions-column';
    /**
     * @var string default CSS class for 'on-hover' actions column
     */
    public $onHoverColumnCSSClass = 'auyii-actions-column-hover';
    /**
     * @var string default CSS class for actions links list
     */
    public $actionsLinksCSSClass = 'auyii-actions-column-links';
    /**
     * @var string default CSS class for collapsed actions menu trigger
     */
    public $collapsedMenuTriggerCSSClass = 'auyii-actions-column-cog';
    /**
     * @var string default CSS class for arrowless collapsed actions menu trigger
     */
    public $collapsedMenuArrowLessCSSClass = 'ayii-dropdown2-trigger-arrowless';
	/**
	 * @var array List of actions in the following format:
	 *
	 *      array(
	 *          array(
	 *              'label' => 'Edit',
	 *
	 *              'url' => $editActionUrl         // string, action URL.
	 *                                              // Use urlExpression if you need to evaluate URL for each row
	 *
	 *              'urlExpression' => function(){} // string PHP expression or callable with
	 *                                              // function($data, $row, $column) signature
	 *                                              // see {@link CComponent::evaluateExpression}
     *
     *              'collapse' => true,             // collapse action in an 'cog' menu, true by default.
     *                                              // works when column $collapse property is set to true.
	 *
	 *              'htmlOptions' => array()        // optional, array of HTML options for the action link tag
	 *          ),
	 *          ...
	 *      )
	 */
	public $actions;
    /**
     * @var bool collapse actions in an 'cog' menu
     */
    public $collapse = false;
    /**
     * @var string icon name for 'cog' menu trigger button. See {@link https://docs.atlassian.com/aui/latest/docs/icons.html}
     */
    public $collapseIcon = 'configure';
    /**
     * @var bool hide dropdown arrow from 'cog' menu trigger button
     */
    public $collapseArrowLess = false;
    /**
     * @var bool show actions on table row hover
     */
    public $onHover = false;


	public function init()
	{
        $columnClass = $this->actionsColumnCSSClass;
        if ($this->onHover)
            $columnClass .= ' ' . $this->onHoverColumnCSSClass;

		if (isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] .= ' ' . $columnClass;
		else
			$this->htmlOptions['class'] = $columnClass;

		parent::init();
	}

	/**
	 * @param int $row
	 * @param mixed $data
	 */
	protected function renderDataCellContent($row, $data)
	{
		if ($actions = $this->renderActions($row, $data))
			echo $actions;
		else
			parent::renderDataCellContent($row, $data);
	}

	/**
     * Render visible and collapsed actions
     *
	 * @param $row
	 * @param $data
	 * @return string
	 */
	protected function renderActions($row, $data)
	{
		return
            $this->renderActionLinks($row, $data) .
            $this->renderCollapsedMenu($row, $data);
	}

    /**
     * Render action links
     *
     * @param $row
     * @param $data
     * @return string
     */
	protected function renderActionLinks($row, $data)
	{
        $actions = $this->getVisibleActions();
        if (!$actions)
            return '';

		$actionLinks = '';
		if ($actions || is_array($actions))
			foreach ($actions as $action)
				$actionLinks .= $this->renderActionLink($action, $row, $data);

		return CHtml::tag('ul', array('class' => $this->actionsLinksCSSClass), $actionLinks);
	}

	/**
	 * Render action link
	 *
	 * @param $action
	 * @param $row
	 * @param $data
	 * @return bool|string
	 */
	protected function renderActionLink($action, $row, $data)
	{
		if (!$this->isActionValid($action))
			return false;

		$actionUrl = $this->getActionUrl($action, $row, $data);
		if (!$actionUrl)
			return false;

		return CHtml::tag('li', array(),
				CHtml::link(
					$action['label'],
					$actionUrl,
					$this->getActionHtmlOptions($action, $row, $data)
				)
		);
	}

	/**
	 * Tell whether given action options are valid
	 *
	 * @param $action
	 * @return bool
	 */
	protected function isActionValid($action)
	{
		return  is_array($action) &&
				isset($action['label']) &&
				(
					(isset($action['url']) && is_string($action['url'])) ||
				    (isset($action['urlExpression']) &&
						    (is_string($action['urlExpression']) || is_callable($action['urlExpression']))
				    )
				);
	}

	/**
	 * Return action link URL
	 *
	 * @param $action
	 * @param $row
	 * @param $data
	 * @return bool|mixed
	 */
	protected function getActionUrl($action, $row, $data)
	{
		if (isset($action['url']))
			return $action['url'];

		if (isset($action['urlExpression']))
			return $this->evaluateExpression($action['urlExpression'], array('data' => $data, 'row' => $row));

		return false;
	}

    /**
     * Return action HTML options
     *
     * @param $action
     * @param $row
     * @param $data
     * @return array
     */
    protected function getActionHtmlOptions($action, $row, $data)
    {
        $options = array();

        if (isset($action['htmlOptions']) && is_array($action['htmlOptions']))
            foreach ($action['htmlOptions'] as $attribute => $value)
                $options[$attribute] = is_callable($value) ?
                    $this->evaluateExpression($value, array('data' => $data, 'row' => $row)) :
                    $value;

        return $options;
    }

    /**
     * Convert action definition to AUIDropdown menu item definition
     *
     * @param $action
     * @param $row
     * @param $data
     * @return array|bool
     */
    protected function convertActionToMenuItem($action, $row, $data)
    {
        if (!$this->isActionValid($action))
   			return false;

        $menuItem = array(
            'title' => $action['label'],
            'url' => $this->getActionUrl($action, $row, $data)
        );

        $menuItem = array_merge($menuItem, $this->getActionHtmlOptions($action, $row, $data));

        return $menuItem;
    }

    /**
     * Render a 'cog' menu
     *
     * @param $row
     * @param $data
     * @return string
     */
    protected function renderCollapsedMenu($row, $data)
    {
        if ($this->collapse) {
            $actions = $this->getCollapsedActions();
            if ($actions)
                return $this->renderCollapsedActions($actions, $row, $data);
        }

        return '';
    }

    /**
     * Render a 'cog' menu actions
     *
     * @param $actions
     * @param $row
     * @param $data
     * @return string
     */
    protected function renderCollapsedActions(&$actions, $row, $data)
    {
        $menuId = uniqid('actionsColumn');

        return
            $this->renderCollapsedMenuTrigger($menuId) .
            $this->renderCollapsedMenuDropdown($menuId, $actions, $row, $data);
    }

    /**
     * Render a 'cog' menu trigger button
     *
     * @param $dropdownId
     * @return mixed
     */
    protected function renderCollapsedMenuTrigger($dropdownId)
    {
        $triggerCSSClass = $this->collapsedMenuTriggerCSSClass;
        if ($this->collapseArrowLess)
            $triggerCSSClass .= ' ' . $this->collapsedMenuArrowLessCSSClass;

        return $this->grid->widget('aui.widgets.AUIButton', array(
            'type' => 'subtle',
            'compact' => true,
            'dropdown' => $dropdownId,
            'icon' => $this->grid->widget('aui.widgets.AUIIcon', array('icon' => $this->collapseIcon), true),
            'htmlOptions' => array(
                'class' => $triggerCSSClass
            )
        ), true);
    }

    /**
     * Render a 'cog' menu dropdown
     *
     * @param $id
     * @param $actions
     * @param $row
     * @param $data
     * @return mixed
     */
    protected function renderCollapsedMenuDropdown($id, &$actions, $row, $data)
    {
        $actionItems = array();
        foreach ($actions as $action)
            $actionItems[] = $this->convertActionToMenuItem($action, $row, $data);

        return $this->grid->widget('aui.widgets.AUIDropdown', array(
            'id' => $id,
            'items' => $actionItems
        ), true);
    }


    /**
     * Get actions which may be collapsed
     *
     * @return array
     */
    protected function getCollapsedActions()
    {
        return array_filter($this->actions, array($this, 'isActionCollapsed'));
    }

    /**
     * Get actions visible as links
     *
     * @return array
     */
    protected function getVisibleActions()
    {
        if (!$this->collapse)
            return $this->actions;

        $_this = $this;
        return array_filter($this->actions, function($action) use($_this) {
            return !$_this->isActionCollapsed($action);
        });
    }

    /**
     * Tell us whether given action may be collapsed
     *
     * @param $action
     * @return bool
     */
    protected function isActionCollapsed($action)
    {
        return !isset($action['collapse']) || ($action['collapse'] === true);
    }
}