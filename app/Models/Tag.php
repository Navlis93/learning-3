<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['text'];

    public function items():BelongsToMany
    {
        return $this->belongsToMany(Todoitem::class, 'tags_items', 'tag_id', 'todoitem_id');
    }

}
