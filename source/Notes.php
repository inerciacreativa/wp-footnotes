<?php

namespace ic\Plugin\Footnotes;

use ic\Framework\Html\Tag;

/**
 * Class Notes
 *
 * @package ic\Plugin\Footnotes
 */
class Notes
{

	/**
	 * @var int
	 */
	private $post_id;

	/**
	 * @var string[]
	 */
	private $notes = [];

	/**
	 * Notes constructor.
	 *
	 * @param int $post_id
	 */
	public function __construct(int $post_id)
	{
		$this->post_id = $post_id;
	}

	/**
	 * @param array       $attributes
	 * @param string|null $content
	 *
	 * @return string|null
	 */
	public function parse(array $attributes, string $content = null): ?string
	{
		$index = 0;

		if (!empty($content)) {
			$index = $this->add($content);
		} else if (isset($attributes['id'])) {
			$index = (int) $attributes['id'];
			if (!$this->has($index)) {
				$index = 0;
			}
		}

		if ($index === 0) {
			return null;
		}

		return $this->getForwardLink($index, $content);
	}

	/**
	 * @return string|null
	 */
	public function generate(): ?string
	{
		if ($this->count() === 0) {
			return null;
		}

		return $this->getList();
	}

	/**
	 * @return int
	 */
	public function count(): int
	{
		return count($this->notes);
	}

	/**
	 * @param string $note
	 *
	 * @return int
	 */
	public function add(string $note): int
	{
		$index = $this->count() + 1;

		$this->notes[$index] = $note;

		return $index;
	}

	/**
	 * @param int $index
	 *
	 * @return bool
	 */
	public function has(int $index): bool
	{
		return array_key_exists($index, $this->notes);
	}

	/**
	 * @param int $index
	 *
	 * @return string|null
	 */
	public function get(int $index): ?string
	{
		return $this->has($index) ? $this->notes[$index] : null;
	}

	/**
	 * @param int    $index
	 * @param string $note
	 *
	 * @return string
	 */
	protected function getForwardLink(int $index, string $note): string
	{
		$link = Tag::a([
			'class' => 'footnote footnote--forward',
			'href'  => $this->getNoteId($index, true),
			'title' => $this->getTitle($note),
		], Tag::sup($index));

		if (!empty($note)) {
			$link['id'] = $this->getReferenceId($index);
		}

		return $link;
	}

	/**
	 * @param int $index
	 *
	 * @return string
	 */
	protected function getBackwardLink(int $index): string
	{
		return Tag::a([
			'class' => 'footnote footnote--backward',
			'href'  => $this->getReferenceId($index, true),
			'title' => __('Back to text', 'ic-footnotes'),
		], '&#8617;');
	}

	/**
	 * @return string
	 */
	protected function getList(): string
	{
		$items = [];
		foreach (array_filter($this->notes) as $index => $note) {
			$items[] = $this->getListItem($index, $note);
		}

		return Tag::div(['class' => 'footnotes'], [
			Tag::h2(['class' => 'footnotes__title'], __('References', 'ic-footnotes')),
			Tag::ol(['class' => 'footnotes__list'], $items),
		]);
	}

	/**
	 * @param int    $index
	 * @param string $note
	 *
	 * @return string
	 */
	protected function getListItem(int $index, string $note): string
	{
		$link = $this->getBackwardLink($index);

		return Tag::li([
			'class' => 'footnotes__item',
			'id'    => $this->getNoteId($index),
		], "$note $link");
	}

	/**
	 * @param string|null $note
	 *
	 * @return string
	 */
	protected function getTitle(string $note = null): string
	{
		return empty($note) ? __('View reference', 'ic-footnotes') : wp_strip_all_tags($note);
	}

	/**
	 * @param int  $index
	 * @param bool $link
	 *
	 * @return string
	 */
	protected function getReferenceId(int $index, bool $link = false): string
	{
		return sprintf('%sreference-%d-%d', $link ? '#' : '', $this->post_id, $index);
	}

	/**
	 * @param int  $index
	 * @param bool $link
	 *
	 * @return string
	 */
	protected function getNoteId(int $index, bool $link = false): string
	{
		return sprintf('%snote-%d-%d', $link ? '#' : '', $this->post_id, $index);
	}

}
