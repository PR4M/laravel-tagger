<?php

namespace Codetensor\Tagger\Models;

use Illuminate\Database\Eloquent\Model;
use Codetensor\Tagger\TagUsedScopeTrait;

class Tag extends Model
{
    use TagUsedScopeTrait;
}
