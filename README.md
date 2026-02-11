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

## Custom Store App Form Fields

Polydock App classes can define custom form fields that appear in the admin panel when configuring a Store App. These fields allow app-specific settings to be collected and stored.

### Defining Custom Fields

1. Add the `#[PolydockAppStoreFields]` attribute to your class
2. Implement the `HasStoreAppFormFields` interface (recommended for type safety)
3. Create static `getStoreAppFormSchema()` and `getStoreAppInfolistSchema()` methods

```php
use FreedomtechHosting\PolydockApp\Attributes\PolydockAppTitle;
use FreedomtechHosting\PolydockApp\Attributes\PolydockAppStoreFields;
use FreedomtechHosting\PolydockApp\Contracts\HasStoreAppFormFields;
use Filament\Forms;
use Filament\Infolists;

#[PolydockAppTitle('My Custom App')]
#[PolydockAppStoreFields]
class MyCustomApp extends PolydockAppBase implements HasStoreAppFormFields
{
    public static function getStoreAppFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Custom Settings')
                ->schema([
                    Forms\Components\TextInput::make('custom_endpoint')
                        ->label('API Endpoint')
                        ->url()
                        ->required(),

                    Forms\Components\TextInput::make('api_secret')
                        ->label('API Secret')
                        ->password()
                        ->extraAttributes(['encrypted' => true]), // Stored encrypted

                    Forms\Components\Select::make('environment')
                        ->label('Environment')
                        ->options([
                            'development' => 'Development',
                            'staging' => 'Staging',
                            'production' => 'Production',
                        ])
                        ->default('production'),
                ]),
        ];
    }

    public static function getStoreAppInfolistSchema(): array
    {
        return [
            Infolists\Components\Section::make('Custom Settings')
                ->schema([
                    Infolists\Components\TextEntry::make('custom_endpoint')
                        ->label('API Endpoint'),

                    Infolists\Components\TextEntry::make('api_secret')
                        ->label('API Secret')
                        ->formatStateUsing(fn ($state) => $state ? '••••••••' : 'Not set'),

                    Infolists\Components\TextEntry::make('environment')
                        ->label('Environment')
                        ->badge(),
                ]),
        ];
    }
}
```

### Field Name Prefixing

All custom field names are automatically prefixed with `app_config_` when stored to avoid collisions with built-in model fields. You should define fields without the prefix - it's added automatically.

For example, a field named `ai_endpoint` will be stored as `app_config_ai_endpoint` in PolydockVariables.

### Encrypted Fields

Mark sensitive fields for encrypted storage by adding the `encrypted` extra attribute:

```php
Forms\Components\TextInput::make('api_key')
    ->password()
    ->extraAttributes(['encrypted' => true])
```

The value will be encrypted before storage and automatically decrypted when retrieved.

### Accessing Custom Field Values

Custom field values are stored as `PolydockVariables` and can be accessed from your app lifecycle methods:

```php
// In your app lifecycle methods
public function preDeployAppInstance(PolydockAppInstanceInterface $appInstance): PolydockAppInstanceInterface
{
    // Get the Store App model and access custom field values (include the prefix)
    $endpoint = $storeApp->getPolydockVariableValue('app_config_custom_endpoint');
    $apiSecret = $storeApp->getPolydockVariableValue('app_config_api_secret'); // Auto-decrypted

    $this->info('Deploying to endpoint: ' . $endpoint);

    return $appInstance;
}
```

### Supported Field Types

Any Filament form component can be used, including:

- `TextInput` - Text, email, URL, password inputs
- `Textarea` - Multi-line text
- `Select` - Dropdown selections
- `Toggle` - Boolean switches
- `Checkbox` / `CheckboxList` - Checkbox inputs
- `Radio` - Radio button groups
- `DatePicker` / `DateTimePicker` - Date inputs
- `FileUpload` - File uploads (stored as paths)
- `KeyValue` - Key-value pair editors
- `Repeater` - Repeatable field groups (stored as JSON)
- `Section` / `Grid` / `Fieldset` - Layout components

### Validation

Use standard Filament validation rules:

```php
Forms\Components\TextInput::make('port')
    ->numeric()
    ->minValue(1)
    ->maxValue(65535)
    ->required(),

Forms\Components\TextInput::make('webhook_url')
    ->url()
    ->rules(['starts_with:https://']),
```
