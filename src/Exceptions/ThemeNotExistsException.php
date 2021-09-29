<?php

namespace IsaEken\ThemeSystem\Exceptions;

use Exception;

class ThemeNotExistsException extends Exception
{
    public function __construct(string|null $name = null, string|null $path = null)
    {
        if ($name !== null && $path !== null) {
            $message = sprintf('The theme %s (%s) not found.', $name, $path);
        } elseif ($name !== null) {
            $message = sprintf('The theme %s not found.', $name);
        } elseif ($path !== null) {
            $message = sprintf('The theme %s not found.', $path);
        }

        parent::__construct($message ?? 'Theme not found.');
    }
}
