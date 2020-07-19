<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

namespace IsaEken\ThemeSystem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class UserTheme
 * @package IsaEken\ThemeSystem\Models
 */
class UserTheme extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'user_themes';

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'theme_id',
        'user_id',
    ];

    /**
     * @var string[] $casts
     */
    protected $casts = [
        'theme_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * @return HasOne|null
     */
    protected function theme() : ?HasOne
    {
        return $this->hasOne('IsaEken\ThemeSystem\Models\Theme', 'id', 'theme_id');
    }

    /**
     * @return HasOne|null
     */
    protected function user() : ?HasOne
    {
        return $this->hasOne('IsaEken\ThemeSystem\Models\User', 'id', 'user_id');
    }
}
