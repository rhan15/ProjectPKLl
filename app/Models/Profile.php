<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    const GENDER_MALE = 'P';
    const GENDER_FEMALE = 'W';
    const GENDER_OTHER = 'PELANGI';


    protected $fillable = [
        'name',
        'gender',
        'birth_date',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
