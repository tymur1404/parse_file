<?php

namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class UserFilter extends AbstractFilter
{

    const CATEGORY = 'category';
    const GENDER = 'gender';
    const BIRTHDATE = 'birthdate';
    const AGE_RANGE = 'age_range';
    const AGE = 'age';

    /**
     * @return array
     */
    protected function getCallback(): array
    {
        return [
            self::CATEGORY => [$this, 'category'],
            self::GENDER => [$this, 'gender'],
            self::BIRTHDATE => [$this, 'birthdate'],
            self::AGE_RANGE => [$this, 'age_range'],
            self::AGE => [$this, 'age'],
        ];
    }

    /**
     * @param Builder $builder
     * @param $value
     * @return void
     */
    protected function category(Builder $builder, $value)
    {
        $builder->where('category_id', $value);
    }

    /**
     * @param Builder $builder
     * @param $value
     * @return void
     */
    protected function birthdate(Builder $builder, $value)
    {
        $builder->where('birthdate', $value);
    }

    /**
     * @param Builder $builder
     * @param $value
     * @return void
     */
    protected function gender(Builder $builder, $value)
    {
        $builder->where('gender', $value);
    }


    /**
     * @param Builder $builder
     * @param $value
     * @return void
     */
    protected function age_range(Builder $builder, $value)
    {
        list($age_min, $age_max) = explode(',', $value);

        $currentDate = Carbon::now();
        $date_from = $currentDate->copy()->subYears($age_max)->startOfYear();
        $date_to = $currentDate->copy()->subYears($age_min)->endOfYear();

        $builder->whereBetween('birthdate',[$date_from, $date_to]);
    }

    /**
     * @param Builder $builder
     * @param $value
     * @return void
     */
    protected function age(Builder $builder, $value)
    {
        $currentDate = Carbon::now();
        $date_from = $currentDate->copy()->subYears($value)->startOfYear();
        $date_to = Carbon::parse($date_from)->addYear();

        $builder->whereBetween('birthdate',[ $date_from, $date_to]);
    }
}
