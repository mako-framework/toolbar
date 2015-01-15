# Mako debug toolbar

## Requirements

Mako 4.4 or greater.

## Installation

1) Add the ```mako\toolbar\ToolbarPackage``` to your ```app/config/application.php``` config file.

2) Render the toolbar. The easiest way of doing this is by adding the following code to your ```app/bootstrap.php``` file:

	$container->get('response')->filter(function($body) use ($container)
	{
		$toolbar = $container->get('toolbar')->render();
		
		return str_replace('</body>', $toolbar . '</body>', $body);
	});
