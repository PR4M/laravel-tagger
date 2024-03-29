<?php

namespace Codetensor\Tagger;

trait TaggableScopeTrait
{
	public function scopeWithAnyTags($query, array $tags)
	{
		return $query->hasTags($tags);
	}

	public function scopeWithAllTags($query, array $tags)
	{
		foreach ($tags as $tag) {
			$query->hasTags([$tag]);
		}

		return $query;
	}

	public function scopeHasTags($query, array $tags)
	{
		return $query->whereHas('tags', function ($query) use ($tags) {
			return $query->whereIn('slug', $tags);
		});
	}
}
