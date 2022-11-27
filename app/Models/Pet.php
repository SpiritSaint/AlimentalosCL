<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $avatar
 * @property string $type
 * @property string $name
 * @property string $description
 */
class Pet extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'avatar',
        'type',
        'name',
        'description',
    ];
}
