# orocommerce-report-plugin
This extension provides six predefined, highly useful reports to help you gain deeper insights into your sales and customers.

## Requirements

| | Version       |
| :--- |:--------------|
| PHP  | 8.2, 8.3, 8.4 |
| OroCommerce | 5.0, 5.1, 6.0 |

## Installation

### Step 1: Add the Repository

Add the plugin's repository to your `composer.json` file:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/pat-lewczuk/orocommerce-report-plugin"
    }
  ]
}
```

If you already have a repositories section in your composer.json, simply append this repository entry to the list.

### Step 2: Require the Plugin

Run the following Composer command to add the plugin as a dependency:

```bash
  composer require pat-lewczuk/orocommerce-report-plugin:dev-master
```

> **NOTE:** Replace **dev-main** with the appropriate branch or version tag if required.

### Step 3: Enable the Plugin

Run the following command to enable the plugin in OroCommerce:

```bash
  php bin/console oro:platform:update --force
```

### Step 4: Clear Cache

Clear the cache to ensure the changes take effect:

```bash
  php bin/console cache:clear
```
## Usage

To be added.

## How to use GrumPHP

* GrumPHP official documentation [Documentation](https://github.com/phpro/grumphp/blob/v2.x/README.md)
* See useful site with command and detailed installation handbook [GrumPHP Command](https://github.com/phpro/grumphp/blob/v2.x/doc/commands.md)

## Contributing

* See [How to contribute](CONTRIBUTING.md)

## License

This library is under the [EUPL-1.2 licence](LICENCE).
