<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Todoitem extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'status', 'user_id'];

    protected $table = 'todoitems';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tags():BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tags_items', 'todoitem_id', 'tag_id');
    }
}
