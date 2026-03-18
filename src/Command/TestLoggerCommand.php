<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Logger\LoggerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-logger',
    description: 'Test the Logger system with SOLID principles'
)]
class TestLoggerCommand extends Command
{
    public function __construct(private readonly LoggerService $logger)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("\n<info>🧪 Testing Logger System (SOLID)</info>\n");
        $output->writeln("═══════════════════════════════════════════\n");

        // Test INFO level
        $output->writeln("<comment>1. Logging INFO message...</comment>");
        $this->logger->info('User logged in', ['user_id' => 123, 'ip' => '192.168.1.1']);

        // Test WARNING level
        $output->writeln("<comment>2. Logging WARNING message...</comment>");
        $this->logger->warning('Low disk space', ['disk' => '/dev/sda1', 'free' => '5%']);

        // Test ERROR level
        $output->writeln("<comment>3. Logging ERROR message...</comment>");
        $this->logger->error('Database connection failed', ['host' => 'db.example.com', 'error' => 'timeout']);

        // Test generic log method
        $output->writeln("<comment>4. Logging with generic method...</comment>");
        $this->logger->log('INFO', 'Application started', ['version' => '1.0.0']);

        $output->writeln("\n═══════════════════════════════════════════");
        $output->writeln("<info>✅ All logs written to:</info>");
        $output->writeln("   • Console (above)");
        $output->writeln("   • File: var/log/app.log");
        $output->writeln("\n<info>📋 SOLID Principles demonstrated:</info>");
        $output->writeln("   • <fg=cyan>S</> - Each writer has ONE job (file/console)");
        $output->writeln("   • <fg=cyan>O</> - Add writers without modifying LoggerService");
        $output->writeln("   • <fg=cyan>L</> - FileWriter & ConsoleWriter are interchangeable");
        $output->writeln("   • <fg=cyan>I</> - LogWriterInterface has only 2 methods");
        $output->writeln("   • <fg=cyan>D</> - LoggerService depends on interfaces\n");

        return Command::SUCCESS;
    }
}
