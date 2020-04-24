<?php

namespace ic\Plugin\Footnotes;

use ic\Framework\Plugin\PluginClass;

/**
 * Class Frontend
 *
 * @package ic\Plugin\Footnotes
 */
class Frontend extends PluginClass
{

	/**
	 * @var Notes
	 */
	private $notes;

	/**
	 * @inheritdoc
	 */
	protected function configure(): void
	{
		parent::configure();

		add_shortcode('footnote', [$this, 'addNote']);

		$this->hook()->before('the_content', 'setNotes');
		$this->hook()->after('the_content', 'getNotes');
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	protected function setNotes(string $content): string
	{
		global $id;

		$this->notes = new Notes($id);

		return $content;
	}

	/**
	 * @param array       $attributes
	 * @param string|null $content
	 *
	 * @return string|null
	 */
	public function addNote($attributes, $content = null): ?string
	{
		return $this->notes->parse((array) $attributes, $content);
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	protected function getNotes(string $content): string
	{
		return $content . $this->notes->generate();
	}

}
