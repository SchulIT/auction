<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

#[AsCommand('app:setup', description: 'Führt finale Installationsschritte aus, bevor die Anwendung ready-to-use ist.')]
class SetupCommand extends Command {

    public function __construct(private readonly Connection        $dbalConnection,
                                private readonly PdoSessionHandler $pdoSessionHandler, private readonly Connection $connection,
                                ?string                            $name = null) {
        parent::__construct($name);
    }

    public function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);

        $this->setupSessions($io);

        return Command::SUCCESS;
    }

    private function setupSessions(SymfonyStyle $io): void {
        $io->section('Sessions');
        $io->write('Prüfe, ob sessions-Tabelle existiert...');
        $sql = "SHOW TABLES LIKE 'sessions';";
        $row = $this->connection->executeQuery($sql);

        if($row->fetchAssociative() === false) {
            $io->writeln('existiert nicht.');
            $io->writeln('Lege Tabelle an...');
            $this->pdoSessionHandler->createTable();
            $io->success('Sessions-Tabelle erstellt');
        } else {
            $io->writeln('existiert.');
            $io->success('Nichts zu tun');
        }
    }
}