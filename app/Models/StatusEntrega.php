<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusEntrega extends Model
{
    use HasFactory;
    public $table = 'status_entregas';

    protected $fillable = [
        'descricao',
        'cor',
    ];
}
