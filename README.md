## Laravel Template - A Blade extension

Laravel Template lets you register helpers to the Blade to integrate your project's modules with your views.

### Examples

**Basic Helper**

Creating a helper is very easy:

	Template::helper('helloworld', function()
	{
		return 'Hello World!';
	});

This would replace:

	{{helloworld}}

**Helper Parameters**

Helpers can have parameters:

	Template::helper('show', function($params)
	{
		return $params['message'];
	});

This would parse:

	{{show message="This is the parameter"}}

**Miscellaneous**

The name of the helper is very flexible:

	Template::helper('cms:module:method', function($params)
	{
		// Do something here
	});

This would parse the following tag:

	{{cms:module:method param1="value1"}}