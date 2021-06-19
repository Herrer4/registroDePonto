<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    use HasFactory;
    public $table = 'historicos';
    public $with = ['status'];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'properties' => 'collection',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];
    protected $fillable =[
        'origem',
        'destino',
        'entrega_id',
        'status_entrega_id',
    ];

    public function status()
    {
        return $this->belongsTo(StatusEntrega::class, 'status_entrega_id');
    }
}
