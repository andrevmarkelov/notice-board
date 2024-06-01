<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    public array $selectable = [
        'id',
        'title',
        'price',
        'photos'
    ];

    protected $fillable = [
        'title',
        'description',
        'price',
        'photos'
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function getMainPhotoAttribute()
    {
        return $this->photos[0] ?? null;
    }

    /**
     * Scope a query to apply sorting based on given fields and order
     *
     * @param $query
     * @param array $sortFields
     * @return mixed
     */
    public function scopeApplySorting($query, array $sortFields): mixed
    {
        foreach ($sortFields as $field => $order) {
            if (in_array($field, ['created_at', 'price']) && in_array($order, ['asc', 'desc'])) {
                $query->orderBy($field, $order);
            }
        }

        return $query;
    }

    /**
     * Retrieve optional fields for the ad
     *
     * @param array $fields
     * @return array
     */
    public function getOptionalFields(array $fields): array
    {
        $optionalFields = [];
        if (in_array('description', $fields)) {
            $optionalFields['description'] = $this->description;
        }
        if (in_array('photos', $fields)) {
            $optionalFields['photos'] = array_slice($this->photos, 1);
        }
        return $optionalFields;
    }
}
