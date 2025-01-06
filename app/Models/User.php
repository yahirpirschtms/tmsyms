<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = "users";
    public $timestamps = false; // Deshabilitar timestamps
 
    // Definir la clave primaria
    protected $primaryKey = 'user_id';
 
     // Si la clave primaria no es un entero, indica que es de tipo string
     //public $incrementing = false;
     protected $keyType = 'string';

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'user_id',
        'username',
        'privilege',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }*/
}
