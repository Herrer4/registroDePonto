<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ponto extends Model
{
    use HasFactory;

    protected $appends = ['totalHoras','day'];

    protected $with = ['user'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'properties' => 'collection',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
        'ponto_1' => 'datetime:d/m/Y H:i:s',
        'ponto_2' => 'datetime:d/m/Y H:i:s',
        'ponto_3' => 'datetime:d/m/Y H:i:s',
        'ponto_4' => 'datetime:d/m/Y H:i:s',
    ];
    protected $fillable =[
        'ponto_1',
        'ponto_2',
        'ponto_3',
        'ponto_4',
        'user_id',
        'justificativa',
    ];

    public function gettotalHorasAttribute()
    {

        if ($this->ponto_1 && $this->ponto_2 && $this->ponto_3 && $this->ponto_4){
            $hora1 = new DateTime($this->ponto_1);

            $hora1 = mktime($hora1->format('H'),$hora1->format('i'),$hora1->format('s'),$hora1->format('m'),$hora1->format('d'),$hora1->format('Y'));

            $hora2 = new DateTime($this->ponto_2);
            $hora2 = mktime($hora2->format('H'),$hora2->format('i'),$hora2->format('s'),$hora2->format('m'),$hora2->format('d'),$hora2->format('Y'));
            $primeiraHora = $hora2 - $hora1;

            $hora3 = new DateTime($this->ponto_3);
            $hora3 = mktime($hora3->format('H'),$hora3->format('i'),$hora3->format('s'),$hora3->format('m'),$hora3->format('d'),$hora3->format('Y'));
            $hora4 = new DateTime($this->ponto_4);
            $hora4 = mktime($hora4->format('H'),$hora4->format('i'),$hora4->format('s'),$hora4->format('m'),$hora4->format('d'),$hora4->format('Y'));

            $segundaHora = $hora4 - $hora3;

            return  (float)gmdate("H.i", $primeiraHora + $segundaHora);
        }else{
            return 0;
        }

    }

    public function getDayAttribute()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



}
