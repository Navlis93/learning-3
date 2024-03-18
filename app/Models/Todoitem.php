<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todoitem extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'status', 'user_id'];

    protected $table = 'todoitems';
}
