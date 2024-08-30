<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use whatwedo\MonitorBundle\Manager\MonitoringManager;
use whatwedo\MonitorBundle\Monitoring\Metric\AbstractMetric;
use whatwedo\MonitorBundle\Monitoring\Sensor\AbstractSensor;
use whatwedo\MonitorBundle\Util\StatusCodeDecider;

#[AsCommand(
    name: 'whatwedo:monitor:check',
    description: 'Check the health of the application'
)]
class CheckCommand extends Command
{
    public function __construct(
        protected int $warningExitCode,
        protected int $criticalExitCode,
        protected MonitoringManager $monitoringManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->printResult($io, $this->monitoringManager->getResult());

        $decider = new StatusCodeDecider($this->monitoringManager, self::SUCCESS, $this->warningExitCode, $this->criticalExitCode);
        return $decider->decide();
    }

    private function printResult(SymfonyStyle $io, $result, $previousGroup = null, $level = 0): void
    {
        foreach ($result as $group => $items) {
            $rows = [];
            $subs = [];
            foreach ($items as $subGroup => $row) {
                if (is_array($row)) {
                    $subs[$subGroup] = $row;

                    continue;
                }

                if ($row instanceof AbstractSensor) {
                    $rows[] = [
                        sprintf('<fg=%s;options=bold>%s</>', $row->getState()->getCliColor(), $row->getState()->getIcon()),
                        $row->getName(),
                        $row->getDetails() ? json_encode($row->getDetails(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '',
                    ];
                } elseif ($row instanceof AbstractMetric) {
                    $rows[] = [
                        sprintf('<fg=%s;options=bold>%s</>', $row->getState()->getCliColor(), $row->getState()->getIcon()),
                        $row->getName(),
                        $row->getValue(),
                    ];
                }
            }

            if ($group !== $previousGroup) {
                if ($level > 0) {
                    $io->writeln($group);
                } else {
                    $io->title(ucfirst($group));
                }
                $previousGroup = $group;
            }

            if (count($rows) > 0) {
                $io->table(['State', 'Name', 'Details'], $rows);
            }

            if (count($subs) > 0) {
                $this->printResult($io, $subs, $previousGroup, ($level + 1));
            }
        }
    }
}
