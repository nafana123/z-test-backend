<?php

namespace App\Command;

use App\Entity\Tender;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-tenders',
    description: 'Импортирует тендеры из CSV файла',
)]
class ImportTendersCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('В процессе...');

        $file = __DIR__ . '/../../test_task_data.csv';

        $handle = fopen($file, 'r');

        while (($data = fgetcsv($handle)) !== false) {
            $tender = new Tender();
            $tender->setExternalCode($data[0]);
            $tender->setNumber($data[1]);
            $tender->setStatus($data[2]);
            $tender->setTitle($data[3]);

            $date = DateTime::createFromFormat('d.m.Y H:i:s', $data[4]);
            $tender->setUpdatedAt($date ?: new DateTime());

            $this->em->persist($tender);
        }

        fclose($handle);
        $this->em->flush();

        $output->writeln('Выполнено.');

        return Command::SUCCESS;
    }
}
