<?php

class AUIDialog extends CComponent
{
	protected $dialog = array();
	protected $currentPage;

	public function addPage($class = '')
	{
		if (!isset($this->dialog['pages']))
			$this->dialog['pages'] = array();

		$this->currentPage = &$this->dialog['pages'][];
		$this->currentPage = array();
		if ($class)
			$this->currentPage['class'] = $class;

		return $this;
	}

	public function addHeader($title, $class = '')
	{
		$header = array('title' => $title);
		if ($class)
			$header['class'] = $class;

		$this->currentPage['header'] = $header;

		return $this;
	}

	public function addPanel($title, $content = '', $class = '')
	{
		$panel = array(
			'title' => $title,
			'content' => $content
		);
		if ($class)
			$panel['class'] = $class;

		if (!isset($this->currentPage['panels']))
			$this->currentPage['panels'] = array();

		$this->currentPage['panels'][] = $panel;

		return $this;
	}

	public function addButton($label, $onClick = '', $class = '')
	{
		$this->addPageButton('button', $label, $onClick, $class);
		return $this;
	}

	public function addLink($label, $onClick = '', $class = '', $url = '')
	{
		$this->addPageButton('link', $label, $onClick, $class, $url);
		return $this;
	}

	public function addSubmit($label, $onClick = '', $class = '')
	{
		$this->addPageButton('submit', $label, $onClick, $class);
		return $this;
	}

	public function addCancel($label, $onClick = '', $class = '')
	{
		$this->addPageButton('cancel', $label, $onClick, $class);
		return $this;
	}

	public function goToPage($index)
	{
		if (isset($this->dialog['pages'][$index]))
			$this->currentPage = &$this->dialog['pages'][$index];

		return $this;
	}

	public function toJSON()
	{
		return CJSON::encode($this->dialog);
	}

	public function render()
	{
		echo $this->toJSON();
	}

	protected function addPageButton($type, $label, $onClick = '', $class = '', $url = '')
	{
		$button = array(
			'type' => $type,
			'label' => $label
		);

		foreach (array('onClick', 'class', 'url') as $param)
			if ($$param !== '')
				$button[$param] = $$param;

		if (!isset($this->currentPage['buttons']))
			$this->currentPage['buttons'] = array();

		$this->currentPage['buttons'][] = $button;
	}
}