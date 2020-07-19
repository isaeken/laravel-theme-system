<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

namespace IsaEken\ThemeSystem\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class User
 * @package IsaEken\ThemeSystem\Models
 */
class User extends \App\User
{
    /**
     * @return HasOne|null
     */
    public function theme() : ?HasOne
    {
        return $this->hasOne('IsaEken\ThemeSystem\Models\UserTheme', 'user_id', 'id');
    }
}
