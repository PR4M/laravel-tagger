<?php

namespace Codetensor\Tagger;

trait TagUsedScopeTrait
{
	public function scopeUsedMoreThanOrEqual($query, $count)
	{
		return $query->where('count', '>=', $count);
	}

	public function scopeUsedMoreThan($query, $count)
	{
		return $query->where('count', '>', $count);
	}

	public function scopeUsedLessThanOrEqual($query, $count)
	{
		return $query->where('count', '<=', $count);
	}

	public function scopeUsedLessThan($query, $count)
	{
		return $query->where('count', '<', $count);
	}
}
