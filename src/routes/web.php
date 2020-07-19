<?php
/**
 * Laravel Theme System
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 */

Route::get('/assets/{asset}', 'IsaEken\ThemeSystem\Controllers\AssetController@index');
