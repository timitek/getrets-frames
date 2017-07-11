<?php

namespace App\Http\Controllers\Views\Models;

/**
 * Used to set custom attributes for common fields on a portal page
 *
 * @author e0174904
 */
class PageParams
{
    /**
     * The parameters to pass to the start function of the angular controller
     * @var type
     */
    public $startupParameters = [];
    public function getStartupParameters()
    {
        return $this->startupParameters;
    }
    public function setStartupParameters($value)
    {
        
        // Return objects as json and everything else as a string wrapped in double quotes
        $formatted = array_map(function ($var) {
            if (is_object($var)) {
                return $var;
            } else if (is_array($var)) {
                return json_encode($var);
            } else {
                return '"' . $var . '"';
            }
        }, $value);
        
        $this->startupParameters = $formatted;
        
        return $this;
    }
}
