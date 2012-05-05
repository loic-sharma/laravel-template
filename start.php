<?php

Autoloader::map(array(
	'Template' => Bundle::path('template').'template'.EXT,
));

Template::_init();