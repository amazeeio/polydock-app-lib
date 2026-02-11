# Polydock App Library

A PHP library providing the core interfaces, base classes, and utilities for building Polydock applications. This library defines the contract for app lifecycle management, instance handling, and integration with the Polydock engine.


## Quick Start

Create a new Polydock app by extending `PolydockAppBase`:

```php
<?php

namespace MyVendor\MyPolydockApp;

use FreedomtechHosting\PolydockApp\Attributes\PolydockAppTitle;
use FreedomtechHosting\PolydockApp\PolydockAppBase;
use FreedomtechHosting\PolydockApp\PolydockAppInstanceInterface;
use FreedomtechHosting\PolydockApp\PolydockAppVariableDefinitionBase;

#[PolydockAppTitle('My Custom App', 'A description of what my app does')]
class MyPolydockApp extends PolydockAppBase
{
    public static string $version = '1.0.0';

    public static function getAppVersion(): string
    {
        return self::$version;
    }

    public static function getAppDefaultVariableDefinitions(): array
    {
        return [
            new PolydockAppVariableDefinitionBase('my-required-variable'),
            new PolydockAppVariableDefinitionBase('another-variable'),
        ];
    }

    public function preCreateAppInstance(PolydockAppInstanceInterface $appInstance): PolydockAppInstanceInterface
    {
        $this->info('Pre-create hook executing...');
        // Your pre-create logic here
        return $appInstance;
    }

    public function createAppInstance(PolydockAppInstanceInterface $appInstance): PolydockAppInstanceInterface
    {
        $this->info('Creating app instance...');
        // Your create logic here
        return $appInstance;
    }

    // Implement other lifecycle methods as needed...
}
```
