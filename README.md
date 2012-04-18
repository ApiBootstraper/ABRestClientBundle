TPRestClientBundle
=====================

This Symfony2 Bundle is based on [educoder][pest]
library for your Symfony project.

Installation
------------

#### With the `deps` file

Add this lines into `deps`:

```ini
[TPRestClientBundle]
    git=http://github.com/TracklineProject/TPRestClientBundle.git
    target=/bundles/TP/Bundle/TPRestClientBundle
```

And run

```bash
./bin/vendors install
```

### Update autoloader & kernel


```php
<?php // app/autoload.php

$loader->registerNamespaces(array(
    // ... 
    'TP'    => __DIR__.'/../vendor/bundles',
));
```

```php
<?php // app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ... 
        new TP\Bundle\TPRestClientBundle\TPRestClientBundle(),
    );
}
```

You can now proceed to the configuration.

Configuration
-------------

```yaml
# app/config/config.yml

tp_rest_client:
    base_url:   'http://api.domain.tld'
```
