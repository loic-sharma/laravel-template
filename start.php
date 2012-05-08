<?php

Autoloader::map(array(
	'Template' => Bundle::path('template').'template'.EXT,
));

Blade::extend(function($content)
{
	return Template::parse($content);
});