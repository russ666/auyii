<?php
/**
 * AUI Dialog2 component implementation
 * See {@link https://docs.atlassian.com/aui/latest/docs/dialog2.html}
 */
class AUIDialog2 extends CWidget
{
	/**
	 * @var string Dialog DOM element id
	 */
	public $id;
	/**
	 * @var string Header title
	 */
	public $header;
	/**
	 * @var string Actions to render to the left of the title
	 */
	public $headerActions;
	/**
	 * @var string Actions to render on the right of the header
	 */
	public $headerSecondary;
	/**
	 * @var bool Show close button
	 */
	public $closeButton = false;
	/**
	 * @var string Main content
	 */
	public $content;
	/**
	 * @var string Dialog hint
	 */
	public $footerHint;
	/**
	 * @var string Actions to render on the right of the footer
	 */
	public $footerActions;

	/**
	 * @var string Dialog size. one of [ 'small', 'medium', 'large', 'xlarge' ]
	 */
	public $size = 'medium';
	/**
	 * @var bool Specifies that the dialog is modal
	 */
	public $modal = true;
	/**
	 * @var bool Remove DOM element when dialog is hidden
	 */
	public $removeOnHide = true;
	/**
	 * @var string Controls the element that is focused when the dialog is opened
	 */
	public $focusSelector;
	/**
	 * @var array Additional attributes for dialog top-level DOM element
	 */
	public $htmlOptions = array();

	/**
	 * @var string Default CSS class for dialog top-level DOM element
	 */
	public $dialogCSSClass = 'aui-layer aui-dialog2';
	/**
	 * @var string Default CSS class for dialog header
	 */
	public $headerCSSClass = 'aui-dialog2-header';
	/**
	 * @var string
	 */
	public $headerTitleCSSClass = 'aui-dialog2-header-main';
	/**
	 * @var string
	 */
	public $headerActionsCSSClass = 'aui-dialog2-header-actions';
	/**
	 * @var string
	 */
	public $headerSecondaryCSSClass = 'aui-dialog2-header-secondary';
	/**
	 * @var string
	 */
	public $closeButtonCSSClass = 'aui-dialog2-header-close';
	/**
	 * @var string
	 */
	public $contentCSSClass = 'aui-dialog2-content';
	/**
	 * @var string
	 */
	public $footerCSSClass = 'aui-dialog2-footer';
	/**
	 * @var string
	 */
	public $footerActionsCSSClass = 'aui-dialog2-footer-actions';
	/**
	 * @var string
	 */
	public $footerHintCSSClass = 'aui-dialog2-footer-hint';


	public function run()
	{
		echo $this->renderDialog();
	}

	/**
	 * Render dialog section
	 *
	 * @return string
	 */
	protected function renderDialog()
	{
		return CHtml::tag(
			'section',
			$this->getDialogOptions(),
			$this->renderHeader() . $this->renderContent() . $this->renderFooter()
		);
	}

	/**
	 * Render dialog header
	 *
	 * @return string
	 */
	protected function renderHeader()
	{
		return CHtml::tag(
			'header',
			array('class' => $this->headerCSSClass),
			$this->renderHeaderTitle() .
			$this->renderHeaderActions() .
			$this->renderHeaderSecondary() .
			$this->renderCloseButton()
		);
	}

	/**
	 * Render dialog title
	 *
	 * @return string
	 */
	protected function renderHeaderTitle()
	{
		return CHtml::tag(
			'h1',
			array('class' => $this->headerTitleCSSClass),
			$this->header
		);
	}

	/**
	 * Render dialog header actions
	 *
	 * @return string
	 */
	protected function renderHeaderActions()
	{
		if (!$this->headerActions)
			return '';

		return CHtml::tag(
			'div',
			array('class' => $this->headerActionsCSSClass),
			$this->headerActions
		);
	}

	/**
	 * Render dialog secondary header actions
	 *
	 * @return string
	 */
	protected function renderHeaderSecondary()
	{
		if (!$this->headerSecondary)
			return '';

		return CHtml::tag(
			'div',
			array('class' => $this->headerSecondaryCSSClass),
			$this->headerSecondary
		);
	}

	/**
	 * Render dialog close button
	 *
	 * @return string
	 */
	protected function renderCloseButton()
	{
		if (!$this->closeButton)
			return '';

		return CHtml::link(
			$this->widget('aui.widgets.AUIIcon', array('icon' => 'close-dialog'), true),
			'',
			array('class' => $this->closeButtonCSSClass)
		);
	}

	/**
	 * Render main content
	 *
	 * @return string
	 */
	protected function renderContent()
	{
		return CHtml::tag(
			'div',
			array('class' => $this->contentCSSClass),
			$this->content
		);
	}

	/**
	 * Render dialog footer
	 *
	 * @return string
	 */
	protected function renderFooter()
	{
		return CHtml::tag(
			'footer',
			array('class' => $this->footerCSSClass),
			$this->renderFooterActions() . $this->renderFooterHint()
		);
	}

	/**
	 * Render dialog footer actions
	 *
	 * @return string
	 */
	protected function renderFooterActions()
	{
		return CHtml::tag(
			'div',
			array('class' => $this->footerActionsCSSClass),
			$this->footerActions
		);
	}

	/**
	 * Render dialog footer hint
	 *
	 * @return string
	 */
	protected function renderFooterHint()
	{
		if (!$this->footerHint)
			return '';

		return CHtml::tag(
			'div',
			array('class' => $this->footerHintCSSClass),
			$this->footerHint
		);
	}

	/**
	 * Returns attributes for dialog top-level DOM element
	 *
	 * @return array
	 */
	protected function getDialogOptions()
	{
		$options = is_array($this->htmlOptions) ? $this->htmlOptions : array();
		$options = array_merge(array(
			'id' => $this->id,
			'role' => 'dialog',
			'aria-hidden' => 'true',
		), $options);

		$dialogClass = $this->dialogCSSClass . ' ' . $this->dialogSizeCSSClass;

		if (isset($options['class']) && strlen($options['class']))
			$options['class'] = $dialogClass . ' ' . $options['class'];
		else
			$options['class'] = $dialogClass;

		if ($this->modal)
			$options['data-aui-modal'] = 'true';
		if ($this->removeOnHide)
			$options['data-aui-remove-on-hide'] = 'true';
		if ($this->focusSelector)
			$options['data-aui-focus-selector'] = $this->focusSelector;

		return $options;
	}

	/**
	 * Returns CSS class for specified dialog size
	 *
	 * @return string
	 */
	protected function getDialogSizeCSSClass()
	{
		return 'aui-dialog2-' . $this->size;
	}
} 