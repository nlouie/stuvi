<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'sex',
        'birthday',
        'title',
        'bio',
        'graduation_date',
        'major',
        'facebook',
        'twitter',
        'linkedin',
        'website'
    ];

}
