<?php

/**
 * Get the path to the storage folder.
 *
 * @param  string  $path
 * @return string
 */
function storage_path($path = '')
{
    return __DIR__.'/../../storage/'.$path;
}