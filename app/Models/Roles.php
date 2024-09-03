<?php

namespace App\Models;


class Roles extends BaseModel
{

    protected $table = "rolles";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        
    ];
    
    public $translatable = [

    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
