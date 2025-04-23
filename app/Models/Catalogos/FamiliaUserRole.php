<?php

namespace App\Models\Catalogos;

use App\Models\User\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamiliaUserRole extends Model{

    use HasFactory, SoftDeletes;
    protected $guard_name = 'web';
    protected $table = 'familia_user_role';

    protected $fillable = [
        'id',
        'familia_id',
        'user_id',
        'role_id',
    ];

    public function familia(){
        return $this->belongsTo(Familia::class);
    }
    public function familias(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(Familia::class, 'familia_user_role', 'user_id', 'familia_id')
            ->withPivot( 'role_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(User::class, 'familia_user_role', 'familia_id', 'user_id')
            ->withPivot('role_id');
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany{
        return $this->belongsToMany(Role::class, 'familia_user_role', 'user_id', 'role_id')
            ->withPivot( 'familia_id');
    }



}
