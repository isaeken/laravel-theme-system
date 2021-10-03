<?php

namespace IsaEken\ThemeSystem\Traits;

use Illuminate\Support\Facades\DB;

/**
 * @property string|null $theme
 */
trait CanChooseTheme
{
    /**
     * Get the theme for model.
     *
     * @return string
     */
    public function getThemeAttribute(): string
    {
        return DB::table('ts_choose_themes')
            ->where('model_id', $this->getAttribute($this->primaryKey))
            ->where('model_type', static::class)
            ->first()?->theme ?? theme_system()->getDefaultTheme();
    }

    /**
     * Change the theme for model.
     *
     * @param  string|null  $theme
     */
    public function setThemeAttribute(string|null $theme): void
    {
        $query = DB::table('ts_choose_themes')
            ->where('model_id', $this->getAttribute($this->primaryKey))
            ->where('model_type', static::class)
            ->first();

        if ($query != null) {
            DB::table('ts_choose_themes')
                ->where('model_id', $this->getAttribute($this->primaryKey))
                ->where('model_type', static::class)
                ->update([
                    'theme' => $theme,
                ]);

            return;
        }

        DB::table('ts_choose_themes')->insert([
            'model_id' => $this->getAttribute($this->primaryKey),
            'model_type' => static::class,
            'theme' => $theme,
        ]);
    }

    public function themeApply(): void
    {
        theme_system()->setTheme($this->getThemeAttribute());
    }
}
