<?php

namespace Codetensor\Tagger;

use Codetensor\Tagger\Models\Tag;

Trait Tagging
{
	public function tags()
	{
		return $this->morphToMany(Tag::class, 'taggable');
	}
}
