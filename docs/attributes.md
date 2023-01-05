# Creating own attributes

Attributes are the core of the bundle. They are used to check the healthiness of the application and to get metrics.

An attribute is either a sensor or a metric. A sensor checks the healthiness of the application. A metric returns a value.

To create own attributes, you must use the following namespace hierarchy:

```text
App\Monitoring\Metric\MyMetricGroup\MyMetric
App\Monitoring\Sensor\MySensorGroup\MySensor
```

- `MyMetricGroup` is the name of the metric group. It is used to group metrics in the dashboard. Use a short name, f.ex. `Queue` or `Database`.
- `MyMetric` is the name of the metric.

You can add as many attributes as you want. The same applies to sensors. You can enable and disable them dynamically. 

⚠️ **Important:** if you use automated checks in Docker or Prometheus and query the API often (f.ex. every 30 seconds), be careful that your attributes does not use too many resources. If you have a metric which queries the database every time, you should probably use a cache and reload every 5 mins.

You can add attributes to your Library and Bundles too, they will be automatically loaded if this bundle is installed.

## Creating a sensor

A sensor checks the healthiness of the application or a service (f.ex. Mercure). 

In the following example we check the availability of third party API.

```php
<?php

declare(strict_types=1);

namespace App\Monitoring\Sensor\ThirdParty;

use App\Api\ServerApi;
use whatwedo\MonitorBundle\Enum\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;

class MyApi extends AbstractSensor
{
    public function __construct(
        protected ServerApi $serverApi
    ) {
    }

    public function getName(): string
    {
        return 'API Availability';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function run(): void
    {
        try {
            $this->details['version'] = $this->serverApi->getVersion();
            $this->state = SensorStateEnum::SUCCESSFUL;
        } catch (ServerApiException $e) {
            $this->details['exception'] = $e->getMessage();
            $this->state = SensorStateEnum::CRITICAL;
        }
    }
}
```


## Creating a metric

A sensor checks a metric in an application. It can be used to check the queue of the message queue or the number of entries in the database.

In the following example we check the availability of third party API.

```php
<?php

declare(strict_types=1);

namespace App\Monitoring\Sensor\Service;

use App\Repository\EmailRepository;
use whatwedo\MonitorBundle\Enum\SensorStateEnum;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;

class EmailQueue extends AbstractMetric
{
    public function __construct(
        protected EmailRepository $emailRepository
    ) {
    }

    public function getName(): string
    {
        return 'Unsent E-Mail Queue';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function run(): void
    {
        $this->value = $this->emailRepository->countUnsentEmails();
        $this->state = match (true) {
            $this->value > 100 => SensorStateEnum::CRITICAL,
            $this->value > 50 => SensorStateEnum::WARNING,
            default => SensorStateEnum::SUCCESSFUL,
        };
    }
}
```
