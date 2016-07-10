<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

class Visitor extends Model
{
    protected $fillable = [
        'ip',
        'item_id',
        'item_type'
    ];

    /**
     * Trigger visit site count
     *
     * @param $item
     */
    public static function visit($item) {
        if (!self::hasVisit($item, get_class($item))) {
            $hit = $item->hit;
            $hit->day += 1;
            $hit->week += 1;
            $hit->month += 1;
            $hit->total += 1;
            $hit->save();
            Visitor::create([
                'ip' => Request::ip(),
                'item_id' => $item->id,
                'item_type' => get_class($item),
            ]);
        }
    }

    /**
     * Determine user has visit the item
     *
     * @param $item
     * @return bool
     */
    public static function hasVisit($item) {
        $ip = Request::ip();
        return Visitor::whereIp($ip)
            ->whereItemId($item->id)
            ->whereItemType(get_class($item))
            ->count() > 0;
    }

    /**
     * Return the specific hit for item
     *
     * @param $item_id
     * @param $item_type
     * @return integer
     */
    public static function count($item_id, $item_type) {
        return Visitor::whereItemId($item_id)
            ->whereItemType($item_type)
            ->count();
    }
    
}
