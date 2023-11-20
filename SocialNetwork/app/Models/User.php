<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $hidden = [
        'email',
        'password',
    ];

    public $fillable = [
        'username',
        'name',
        'surname',
        'dateOfBirth',
        'phoneNumber'
    ];
}
