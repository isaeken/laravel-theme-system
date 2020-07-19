<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

namespace IsaEken\ThemeSystem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

/**
 * Class Theme
 * @package IsaEken\ThemeSystem\Models
 */
class Theme extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'themes';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'state',
        'default',
        'name',
        'details',
        'settings',
    ];

    /**
     * @var array $casts
     */
    protected $casts = [
        'default' => 'boolean',
        'details' => 'array',
        'settings' => 'array',
    ];

    /**
     * @return HasMany|null
     */
    public function users() : ?HasMany
    {
        return $this->hasMany('IsaEken\ThemeSystem\Models\UserTheme', 'theme_id', 'id');
    }

    /**
     * @return object|null
     */
    public function details() : ?object
    {
        if (!isset($this->details) || $this->details == null) return null;
        return (object) $this->details;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function detail($key)
    {
        if (!isset($this->details) || $this->details == null) return null;
        if (!array_key_exists($key, $this->details)) return null;
        return $this->details[$key];
    }

    /**
     * @return mixed
     */
    public function settings()
    {
        if (!isset($this->settings) || $this->settings == null) return null;
        return $this->settings;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function setting($key)
    {
        return $this->getSetting($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getSetting($key)
    {
        if (!isset($this->settings) || $this->settings == null) return null;
        if (!array_key_exists($key, $this->settings)) return null;
        return $this->settings[$key];
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setSetting($key, $value)
    {
        if (!isset($this->settings) || $this->settings == null) return null;
        $settings = $this->settings;
        if (!array_key_exists($key, $settings)) $settings = Arr::add($settings, $key, $value);
        else $settings = Arr::set($settings, $key, $value);
        $this->settings = $settings;
        $this->save();
        return $this->getSetting($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function removeSetting($key)
    {
        if (!isset($this->settings) || $this->settings == null) return null;
        $settings = $this->settings;
        if (array_key_exists($key, $settings)) $settings = Arr::pull($settings, $key);
        $this->settings = $settings;
        return $this->settings();
    }
}
