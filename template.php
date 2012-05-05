<?php

class Template {

	/**
	 * All of the helpers that have been registered.
	 *
	 * @var array
	 */
	static public $helpers = array();

	/**
	 * Register the Blade extension.
	 *
	 * @return void
	 */
	public static function _init()
	{
		Blade::extend(function($value)
		{
			foreach(Template::$helpers as $name => $helper)
			{
				// Grab all of the tags that match the helper's name. As there
				// may be many registered helpers, it is optimal to verify the
				// tag exists before running any regular expressions.
				if(strpos($value, '{{'.$name))
				{
					while(preg_match('/\{\{'.$name.'(.*?)\}\}/', $value, $matches, PREG_OFFSET_CAPTURE))
					{
						$params = trim($matches[1][0]);

						if( ! empty($params))
						{
							// The tag's parameters need to be converted into a PHP array.
							// Single quotes will need to be backslashed to prevent them
							// from accidentally escaping out.
							$params = addcslashes($params, '\'');
							$params = preg_replace('/(.*?)="(.*?)"/', '\'$1\'=>\'$2\',', $params);
							$params = substr($params, 0, -1);
						}

						$replace = '<?php echo \\Template::call(\''.$name.'\', array('.$params.')); ?>';

						$value = str_replace($matches[0], $replace, $value);
					}
				}
			}

			return $value;
		});
	}

	/**
	 * Register a template helper.
	 *
	 * @param  string   $name
	 * @param  Closure  $helper
	 * @return void
	 */  
	public static function helper($name, Closure $helper)
	{
		static::$helpers[$name] = $helper;
	}

	/**
	 * Call a template helper.
	 *
	 * @param  string  $name
	 * @param  array   $params
	 * @return mixed
	 */
	public static function call($name, $params = array())
	{
		$helper = static::$helpers[$name];

		return $helper($params);
	}
}