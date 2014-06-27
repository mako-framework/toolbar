# Mako debug toolbar

## Requirements

Mako 4.0 or greater.

## Installation

1. Add the ```mako\toolbar\ToolbarService``` to your ```app/config/application.php``` config file.
2. Render the toolbar. The easiest way if doing this is by adding the following code to your ```app/bootstra.php``` file:

</span>

	$container->get('response')->filter(function($body) use ($container)
	{
		$toolbar = $container->get('toolbar')->render();
		
		return str_replace('</body>', $toolbar . '</body>', $body);
	});