<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemList extends Model
{
	protected $table = 'lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'user_id', 'latitude', 'longitude', 'due_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'user_id'];

    public function items ()
    {
    	return $this->belongsToMany('App\Item', 'lists_items', 'list_id', 'item_id')->withPivot('quantity');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
}
