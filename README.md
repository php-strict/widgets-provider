# Widgets Provider

[![Software License][ico-license]](LICENSE.txt)

Widgets Provider repository.

This repository contains interfaces of widgets provider, widgets data storage and widget.
Alsow it contains basic implementation of this interfaces.

Widgets providers works with widgets data storage. Widgets data storage 
stores information about widgets scope, place and some widget type 
dependent parameters. Widgets provider refers to the 
[widgets producers](https://github.com/php-strict/widgets-producer), 
that are encapsulated in widgets data, for creating the widgets.

Widgets provider can take [widgets consumer](https://github.com/php-strict/widgets-consumer)
object as parameter of setWidgets method and inject widgets into it.

See main [widgets](https://github.com/php-strict/widgets) repository 
for detail description and examples.

## Requirements

*   PHP >= 7.1

## Install

Install with [Composer](http://getcomposer.org):

```bash
composer require php-strict/widgets-provider
```

[ico-license]: https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat-square
