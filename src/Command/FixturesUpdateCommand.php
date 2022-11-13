<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fixtures:update',
    description: 'Dump DB, udpdate schema and load fixtures.',
)]
class FixturesUpdateCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp("This command allows you to update the fixtures by dumping the database, update the schema and load new fixtures.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Updating fixtures...');

        $io->section('Dumping database...');
        $this->getApplication()->find('doctrine:database:drop')->run(new ArrayInput(['--force' => true]), $output);
        $io->info('Database dropped.');

        $io->section('Schema update...');
        $this->getApplication()->find('doctrine:schema:update')->run(new ArrayInput(['--force' => true]), $output);
        $io->info('Schema updated.');

        $io->section('Loading fixtures...');
        $this->getApplication()->find('doctrine:fixtures:load')->run(new ArrayInput(['--no-interaction' => true]), $output);
        $io->info('Fixtures loaded.');

        $io->success('All done, you database is ready to be used !');

        return Command::SUCCESS;
    }
}
