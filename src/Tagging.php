<?php

namespace Codetensor\Tagger;

use Codetensor\Tagger\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

Trait Tagging
{
	/**
	 * Return collection of tagged rows related to the tagged model
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function tags()
	{
		return $this->morphToMany(Tag::class, 'taggable');
	}


	/**
	 *
	 */
	public function tag($tags)
	{
		$this->addTags($this->getWorkableTags($tags));
	}

	public function untag($tags = null)
	{
		if ($tags === null) {
			$this->removeAllTags();
			return;
		}

		$this->removeTags($this->getWorkableTags($tags));
	}

	private function removeTags(Collection $tags)
	{
		$this->tags()->detach($tags);

		foreach ($tags->where('count', '>', 0) as $tag) {
			$tag->decrement('count');
		}
	}

	private function removeAllTags()
	{
		$this->removeTags($this->tags);
	}

	/**
	 *
	 */
	private function addTags(Collection $tags)
	{
		$sync = $this->tags()->syncWithoutDetaching($tags->pluck('id')->toArray());

		foreach (Arr::get($sync, 'attached') as $attachId) {
			$tags->where('id', $attachId)->first()->increment('count');
		}
	}

	/**
	 *
	 */
	private function getWorkableTags($tags)
	{
		if (is_array($tags)) {
			return $this->getTagModels($tags);
		}

		if ($tags instanceof Model) {
			return $this->getTagModels([$tags->slug]);
		}

		return $this->filterTagsCollection($tags);
	}

	private function filterTagsCollection(Collection $tags)
	{
		return $tags->filter(function ($tag) {
			return $tag instanceof Model;
		});
	}

	/**
	 *
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	private function getTagModels(array $tags)
	{
		return Tag::whereIn('slug', $this->normaliseTagNames($tags))->get();
	}

	/**
	 * Normalise the tag names to be slugs
	 *
	 * @return array
	 */
	private function normaliseTagNames(array $tags)
	{
		return array_map(function ($tag) {
			return Str::slug($tag);
		}, $tags);
	}
}
