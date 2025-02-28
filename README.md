# Mako debug toolbar

[![Static analysis](https://github.com/mako-framework/toolbar/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/mako-framework/toolbar/actions/workflows/static-analysis.yml)

## Requirements

Mako 11.0 or greater.

## Installation

Install the package using the following composer command:

	composer require mako/toolbar

Next, add the ```mako\toolbar\ToolbarPackage``` package to your ```app/config/application.php``` config file.

You should make sure that the middleware gets executed first to ensure that the toolbar is able to collect all the information about your application.

	$dispatcher->setMiddlewarePriority(ToolbarMiddleware::class, 1);

You can now add the middleware to the routes of your choice or make it global if you want to apply it to all your routes.

	$dispatcher->registerGlobalMiddleware(ToolbarMiddleware::class);

> The middleware will only append the toolbar to responses with a content type of `text/html` and a body that includes a set of `<body></body>` tags.
