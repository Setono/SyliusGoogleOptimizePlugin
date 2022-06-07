# Google Optimize Plugin for Sylius

[![Latest Version][ico-version]][link-packagist]
[![Latest Unstable Version][ico-unstable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Code Coverage][ico-code-coverage]][link-code-coverage]

Integrate [Google Optimize](https://optimize.google.com) with your Sylius store the _right_ way!

## Installing the plugin

```shell
composer require setono/sylius-google-optimize-plugin
```

## Enabling the plugin

If you have Flex enabled the `composer require` will automatically add the bundles and the plugin to `bundles.php`.
If not you should manually add them:

```php
    // ...

    Setono\SyliusGoogleOptimizePlugin\SetonoSyliusGoogleOptimizePlugin::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
    
    // ...
```

**NOTICE:** It's important that you add the plugin _before_ the `SyliusGridBundle`.

## Add configuration file

Create the file `config/packages/setono_sylius_google_optimize.yaml` and add the following:

```yaml
# config/packages/setono_sylius_google_optimize.yaml
imports:
    - { resource: "@SetonoSyliusGoogleOptimizePlugin/Resources/config/app/config.yaml" }
```

## Include routes configuration

Create the file `config/routes/setono_sylius_google_optimize.yaml` and add the following:

```yaml
# config/routes/setono_sylius_google_optimize.yaml
setono_sylius_google_optimize:
    resource: "@SetonoSyliusGoogleOptimizePlugin/Resources/config/routes.yaml"
```

The plugin also provides a routes file for non localized stores. All you do is to use
`@SetonoSyliusGoogleOptimizePlugin/Resources/config/routes_no_locale.yaml` instead of
`@SetonoSyliusGoogleOptimizePlugin/Resources/config/routes.yaml`


[ico-version]: https://poser.pugx.org/setono/sylius-google-optimize-plugin/v/stable
[ico-unstable-version]: https://poser.pugx.org/setono/sylius-google-optimize-plugin/v/unstable
[ico-license]: https://poser.pugx.org/setono/sylius-google-optimize-plugin/license
[ico-github-actions]: https://github.com/Setono/SyliusGoogleOptimizePlugin/workflows/build/badge.svg
[ico-code-coverage]: https://codecov.io/gh/Setono/SyliusGoogleOptimizePlugin/branch/master/graph/badge.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-google-optimize-plugin
[link-github-actions]: https://github.com/Setono/SyliusGoogleOptimizePlugin/actions
[link-code-coverage]: https://codecov.io/gh/Setono/SyliusGoogleOptimizePlugin
