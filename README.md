# Guzzle Header Forwarding Plugin

This plugin integrates a way to forward headers from the current symfony request into the cURL.


## Requirements
 - PHP 7.0 or above
 - Symfony 4.0 or above
 - [Guzzle Bundle][1]

 
### Installation
Using [composer][2]:

##### composer.json
``` json
{
    "require": {
        "sbutnariu/guzzle-header-forward-plugin": "^1.0"
    }
}
```

##### command line
``` bash
$ composer require sbutnariu/guzzle-header-forward-plugin
```

## Usage
### Enable bundle
In ```bin/Kernel.php``` find the lines that registers bundles:
``` php
# bin/Kernel.php
foreach ($contents as $class => $envs) {
    if (isset($envs['all']) || isset($envs[$this->environment])) {
        yield new $class();
    }
}
```
and replace them with:
``` php
foreach ($contents as $class => $envs) {
    if (isset($envs['all']) || isset($envs[$this->environment])) {
        if ($class === \EightPoints\Bundle\GuzzleBundle\EightPointsGuzzleBundle::class) {
            yield new $class([
                new SilviuButnariu\GuzzleHeaderForwardPlugin\Plugin(),
            ]);
        } else {
            yield new $class();
        }
    }
}
```

### Basic configuration
``` yaml
# app/config/config.yml

eight_points_guzzle:
    clients:
        api_payment:
            base_url: "http://api.domain.tld"

            # define headers, options

            # plugin settings
            plugin:
                header_forward:
                    enabled: true
                    headers:
                        - 'Accept-Language'
```

[1]: https://github.com/8p/EightPointsGuzzleBundle
[2]: https://getcomposer.org/
