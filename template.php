<?php

class Template {

	/**
	 * All of the helpers that have been registered.
	 *
	 * @var array
	 */
	static public $helpers = array();

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
	 * Parse the content for template tags.
	 *
	 * @return string
	 */
	public static function parse($content)
	{
		if(count(static::$helpers) == 0)
		{
			// The regular expression will match all Blade tags if there are
			// no helpers. To prevent this from happening, parsing will be
			// forced to end here.
			return $content;
		}

		$names = array();

		foreach(static::$helpers as $name => $helper)
		{
			$names[] = preg_quote($name, '/');
		}

		$regexp = '/\{\{('.implode('|', $names).')(.*?)\}\}/u';

		return preg_replace_callback($regexp, function($match)
		{
			list(, $name, $params) = $match;

			if( ! empty($params))
			{
				// The tag's parameters need to be converted into a PHP array.
				// Single quotes will need to be backslashed to prevent them
				// from accidentally escaping out.
				$params = addcslashes($params, '\'');
				$params = preg_replace('/ (.*?)="(.*?)"/', '\'$1\'=>\'$2\',', $params);
				$params = substr($params, 0, -1);
			}

			return '<?php echo \\Template::call(\''.$name.'\', array('.$params.')); ?>';
		}, $content);
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