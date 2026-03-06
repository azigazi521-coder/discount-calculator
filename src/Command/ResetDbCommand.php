<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Doctrine\Persistence\ManagerRegistry;



#[AsCommand(
    name: 'app:reset-db',
    description: 'Add a short description for your command',
)]
class ResetDbCommand extends Command
{
    public function __construct(private ManagerRegistry $doctrine)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Resetowanie bazy danych (DELETE + ALTER TABLE)');

        $connection = $this->doctrine->getConnection();

        $io->section('Wyłączanie sprawdzania kluczy obcych');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');

        $io->section('Usuwanie danych z tabel');
        $connection->executeStatement('DELETE FROM product_promotion');
        $connection->executeStatement('DELETE FROM product');
        $connection->executeStatement('DELETE FROM promotion');

        $io->section('Resetowanie AUTO_INCREMENT');
        $connection->executeStatement('ALTER TABLE product AUTO_INCREMENT = 1');
        $connection->executeStatement('ALTER TABLE promotion AUTO_INCREMENT = 1');
        $connection->executeStatement('ALTER TABLE product_promotion AUTO_INCREMENT = 1');

        $io->section('Włączanie sprawdzania kluczy obcych');
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

        $io->success('Tabele zostały wyczyszczone i ID zresetowane.');

        $io->section('Ładowanie fixtures');

        // Uruchamiamy doctrine:fixtures:load
        $process = new Process(['php', 'bin/console', 'doctrine:fixtures:load', '--no-interaction']);
        $process->setTimeout(null); // wyłącz timeout
        $process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        $io->success('Fixtures zostały załadowane.');

        return Command::SUCCESS;
    }
}
