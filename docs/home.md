# Getting Started

This bundle enables application monitoring features:

- **Sensors** to check the healthiness
- **Metrics** to get metrics (f.ex. number of messages in the queue)

the bundle provides multiple methods to get the state with all sensors and metrics:

- **Dashboard** to integrate it into the application (tailwind design given)
- **Command** access (`bin/console whatwedo:monitor:check`) which returns a text based overview and returns exit code 0 or 1 to integrate it into f.ex. docker health checks
- **HTTP API (JSON/XML) Endpoint** which returns 200 or 503 HTTP code to integrate it into the monitoring software (f.ex. Prometheus)

The bundle comes with some default monitoring sensors to check f.ex. the state of your Doctrine Migrations – please send us a Merge Request with other generic tests. Check out [`src/Monitoring`](https://github.com/whatwedo/MonitorBundle/tree/main/src/Monitoring) for examples and predefined sensors.

## Installation

### Composer
```
composer require whatwedo/monitor-bundle
```

### Enable the dashboard
Add this to your routing configuration:

```yml
whatwedo_monitor_dashboard:
  controller: whatwedo\MonitorBundle\Controller\DashboardController
  path: /dashboard
```
### Enable the API
Add this to your routing configuration:

```yml
whatwedo_monitor_api:
    controller: whatwedo\MonitorBundle\Controller\ApiController
    path: /api/check.{_format}
    requirements:
        _format: json|xml
```

## Configure predefined attributes
There are predefined attributes. For example there is an attribute which checks the database connection if Doctrine DBAL is installed.

Check the configuration reference to configure predefined attributes.

```
bin/console config:dump-reference whatwedo_monitor
```

## Getting started

- [Checking states (Dashboard, Command line, API)](checking-states.md)
- [Creating own attributes (metrics / sensors)](attributes.md)
