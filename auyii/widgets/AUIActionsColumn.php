<?php

class AUIActionsColumn extends CDataColumn
{
	/**
	 * @var string default CSS class for actions column
	 */
	public $actionsColumnClass = 'aui-compact-button-column';

	/**
	 * @var array List of action links in the following format:
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
	 *              'htmlOptions' => array()        // optional, array of HTML options for the action link tag
	 *          ),
	 *          ...
	 *      )
	 */
	public $links;

	/**
	 * @var
	 */
	public $cog;


	public function init()
	{
		if (isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] .= ' ' . $this->actionsColumnClass;
		else
			$this->htmlOptions['class'] = $this->actionsColumnClass;

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
	 * @param $row
	 * @param $data
	 * @return string
	 */
	protected function renderActions($row, $data)
	{
		return $this->renderActionLinks($row, $data);
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
		$actionLinks = '';

		if ($this->links || is_array($this->links))
			foreach ($this->links as $link)
				$actionLinks .= $this->renderActionLink($link, $row, $data);

		return CHtml::tag('ul', array('class' => 'auyii-actions-column-links'), $actionLinks);
	}

	/**
	 * Render action link
	 *
	 * @param $link
	 * @param $row
	 * @param $data
	 * @return bool|string
	 */
	protected function renderActionLink($link, $row, $data)
	{
		if (!$this->isLinkValid($link))
			return false;

		$actionUrl = $this->getLinkActionUrl($link, $row, $data);
		if (!$actionUrl)
			return false;

		return CHtml::tag('li', array(),
				CHtml::link(
					$link['label'],
					$actionUrl,
					isset($link['htmlOptions']) ? $link['htmlOptions'] : array()
				)
		);
	}

	/**
	 * Tell whether given link options are valid
	 *
	 * @param $link
	 * @return bool
	 */
	protected function isLinkValid($link)
	{
		return  is_array($link) &&
				isset($link['label']) &&
				(
					(isset($link['url']) && is_string($link['url'])) ||
				    (isset($link['urlExpression']) &&
						    (is_string($link['urlExpression']) || is_callable($link['urlExpression']))
				    )
				);
	}

	/**
	 * Return link action URL
	 *
	 * @param $link
	 * @param $row
	 * @param $data
	 * @return bool|mixed
	 */
	protected function getLinkActionUrl($link, $row, $data)
	{
		if (isset($link['url']))
			return $link['url'];

		if (isset($link['urlExpression']))
			return $this->evaluateExpression($link['urlExpression'], array('data' => $data, 'row' => $row));

		return false;
	}
}