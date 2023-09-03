# Mako debug toolbar

[![Static analysis](https://github.com/mako-framework/toolbar/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/mako-framework/toolbar/actions/workflows/static-analysis.yml)

## Requirements

Mako 10.0 or greater.

## Installation

Install the package using the following composer command:

	composer require mako/toolbar

Next, add the ```mako\toolbar\ToolbarPackage``` package to your ```app/config/application.php``` config file.

Finally, you need to make sure that the toolbar gets rendered. The quickest way of getting it up and running is to use the included middleware.

	$dispatcher->registerMiddleware('toolbar', ToolbarMiddleware::class);

You should make sure that the middleware gets executed first to ensure that the toolbar is able to collect all the information about your application.

	$dispatcher->setMiddlewarePriority(['toolbar' => 1]);

You can now add the middleware to the routes of your choice or make it global if you want to apply it to all your routes.

	$dispatcher->setMiddlewareAsGlobal(['toolbar']);

> The middleware will only append the toolbar to responses with a content type of `text/html` and a body that includes a set of `<body></body>` tags.
