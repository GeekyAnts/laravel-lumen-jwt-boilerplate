<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest as Middleware;

class TransformRequest extends Middleware
{
	protected function transform($key, $value)
	{
		/**
		 * If you're sending boolean or boolean as string, 
		 * it will be converted to the boolean.
		 */
		if ($value === 'true' || $value === 'TRUE') return true;
		if ($value === 'false' || $value === 'FALSE') return false;
		
		return $value;
	}
}