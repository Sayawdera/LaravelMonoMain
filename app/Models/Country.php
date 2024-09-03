<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{belongsTo, hasMany};

class Country extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'country',
        'state',
        'city',
        'street',
        'zip_code',
        'home_number',
        'country_id',
        'created_at',
        'updated_at',
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

    public function ParentCountry(): belongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function SubCountries(): hasMany
    {
        return $this->hasMany(Country::class, 'country_id');
    }
}
