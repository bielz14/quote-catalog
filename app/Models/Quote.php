<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Quote
 *
 * @property int     $id
 * @property string  $title
 * @property string  $description
 * @property int     $user_id
 *
 * @package App\Models
 */
class Quote extends Model
{
    use HasFactory;

    public $table = 'quotes';

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'user_id' => 'int'
    ];

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
