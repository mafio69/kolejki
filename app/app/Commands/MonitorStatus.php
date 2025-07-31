<?php

namespace App\Commands;

use App\Services\PerformanceService;
use App\Services\PersonnelService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use React\EventLoop\Loop;
use App\Libraries\RedisClientInterface;
use App\Libraries\RedisClientAdapter;
use Config\Services;

class MonitorStatus extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'monitor:status';
    protected $description = 'Asynchronously monitors and displays the status of all roller coasters.';

    private function getClient(): RedisClientInterface
    {
        return Services::redisClient();
    }

    public function run(array $params)
    {
        $loop = Loop::get();

        $client = $this->getClient();

        $client->on('error', function (\Exception $e) {
            CLI::error("Redis error: " . $e->getMessage());
        });

        $client->ping()->then(
            function () use ($loop, $client) {
                CLI::write("Successfully connected to Redis. Starting monitoring...");
                $loop->addPeriodicTimer(5, function () use ($client) {
                    $this->fetchAndDisplayCoasterStatus($client);
                });
            },
            function (\Exception $e) use ($loop) {
                CLI::error("Could not connect to Redis: " . $e->getMessage());
                $loop->stop();
            }
        );

        $loop->run();
    }

    public function stopLoop()
    {
        Loop::get()->stop();
    }

    private function fetchAndDisplayCoasterStatus(RedisClientInterface $client)
    {
        $client->keys('coaster:*')->then(function ($keys) use ($client) {
            $coasterKeys = array_filter($keys, fn($key) => !str_contains($key, ':wagons'));

            if (empty($coasterKeys)) {
                CLI::write('No coasters found to monitor.');
                return;
            }

            CLI::clearScreen();
            CLI::write("Monitoring Status @ " . date('Y-m-d H:i:s'));
            CLI::newLine();

            foreach ($coasterKeys as $coasterKey) {
                $client->hgetall($coasterKey)->then(function ($coasterData) use ($client, $coasterKey) {
                    if (!empty($coasterData)) {
                        $coasterId = str_replace('coaster:', '', $coasterKey);
                        $this->displayCoasterInfo($client, $coasterId, $coasterData);
                    }
                });
            }
        });
    }

    private function displayCoasterInfo(RedisClientInterface $client, string $coasterId, array $coasterData)
    {
        $personnelService = new PersonnelService();
        $performanceService = new PerformanceService();

        $client->smembers('coaster:' . $coasterId . ':wagons')->then(function ($wagonIds) use ($client, $personnelService, $performanceService, $coasterId, $coasterData) {
            $wagonPromises = [];
            foreach ($wagonIds as $wagonId) {
                $wagonPromises[] = $client->hgetall('wagon:' . $wagonId);
            }

            \React\Promise\all($wagonPromises)->then(function ($wagonsData) use ($personnelService, $performanceService, $coasterId, $coasterData, $wagonIds) {
                $wagonsCount = count($wagonIds);
                $requiredPersonnel = $personnelService->calculateRequiredPersonnel($wagonsCount);

                $dailyCapacity = $performanceService->calculateDailyCapacity($coasterData, $wagonsData);
                $performanceData = $performanceService->checkPerformance($dailyCapacity, $coasterData['liczba_klientow']);

                CLI::write(sprintf('[Kolejka %s]', $coasterId));
                CLI::write(sprintf('  Godziny działania: %s - %s', $coasterData['godziny_od'], $coasterData['godziny_do']));
                CLI::write(sprintf('  Liczba wagonów: %d', $wagonsCount));
                CLI::write(sprintf('  Dostępny personel: %d/%d', $coasterData['liczba_personelu'], $requiredPersonnel));
                CLI::write(sprintf('  Klienci dziennie: %d', $coasterData['liczba_klientow']));
                CLI::write(sprintf('  Przewidywana przepustowość: %d', $dailyCapacity));

                $problems = [];
                $personnelShortage = $requiredPersonnel - $coasterData['liczba_personelu'];
                if ($personnelShortage > 0) {
                    $problems[] = "Brakuje {$personnelShortage} pracowników";
                }

                if ($performanceData['status'] !== 'ok') {
                    $problems[] = $performanceData['message'];
                }

                $status = 'OK';
                if (!empty($problems)) {
                    $status = 'Problem: ' . implode(', ', $problems);
                    $this->logProblem($coasterId, $status);
                }

                CLI::write('  Status: ' . $status);
                CLI::newLine();
            });
        });
    }

    private function logProblem(string $coasterId, string $problem)
    {
        log_message('warning', sprintf('Kolejka %s - %s', $coasterId, $problem));
    }
}
