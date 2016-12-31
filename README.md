# Mako debug toolbar

## Requirements

Mako 5.0 or greater.

## Installation

1) Add the ```mako\toolbar\ToolbarPackage``` package to your ```app/config/application.php``` config file.

2) Render the toolbar. The easiest way of doing it is by adding the following code to your ```app/bootstrap.php``` file:

	$container->get('response')->filter(function($body) use ($container)
	{
		$toolbar = $container->get('toolbar');

		return str_replace('</body>', $toolbar->render() . '</body>', $body);
	});

3) The `included files` panel is disabled by default. To enable it just add the following line of code.

	$toolbar->addPanel(new IncludedFilesPanel($container->get('view')));
