[![Latest Stable Version](https://poser.pugx.org/whatwedo/monitor-bundle/v/stable)](https://packagist.org/packages/whatwedo/monitor-bundle)
[![SymfonyInsight](https://insight.symfony.com/projects/b36c4c03-ddc3-42dc-b86f-d9263c36dcc2/mini.svg)](https://insight.symfony.com/projects/b36c4c03-ddc3-42dc-b86f-d9263c36dcc2)
[![codecov](https://codecov.io/github/whatwedo/MonitorBundle/branch/main/graph/badge.svg?token=U0LO4ZH9ZG)](https://codecov.io/github/whatwedo/MonitorBundle)

# whatwedoMonitorBundle

This bundle enables application monitoring features:

- **Sensors** to check the healthiness
- **Metrics** to get metrics (f.ex. number of messages in the queue)

the bundle provides multiple methods to get the state with all sensors and metrics:

- **Dashboard** to integrate it into the application (tailwind design given)
- **Command** access (`bin/console whatwedo:monitor:check`) which returns a text based overview and returns exit code 0 or 1 to integrate it into f.ex. docker health checks
- **HTTP API (JSON/XML) Endpoint** which returns 200 or 503 HTTP code to integrate it into the monitoring software (f.ex. Prometheus)

![Screenshot Dashboard](docs/images/screenshot_dashboard.png)

## Documentation

see https://whatwedo.github.io/MonitorBundle/

## License

This bundle is under the MIT license. See the complete license in the bundle: [LICENSE](LICENSE)

## Maintainer

This bundle is maintained by [whatwedo GmbH](https://whatwedo.ch), a software studio based in Bern, Switzerland.
