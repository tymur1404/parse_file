<?php

namespace App\Models;


use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Filterable;

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';


    protected $guarded = false;

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function getMinMaxAge(): array
    {
        $users = self::all();
        $ages = $users->map(function ($user) {
            return $user->birthdate->age;
        });

        $minAge = $ages->min();
        $maxAge = $ages->max();

        return [
            'minAge' => $minAge,
            'maxAge' => $maxAge
        ];
    }

}
