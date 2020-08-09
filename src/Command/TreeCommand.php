<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TreeCommand extends Command
{
    protected function configure()
    {
        $this->setName('app:tree:fill')
            ->addArgument('treeFilePath', InputArgument::REQUIRED, 'tree file')
            ->addArgument('listFilePath', InputArgument::REQUIRED, 'list file')
            ->addArgument('resultFilePath', InputArgument::OPTIONAL, 'outputFile');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tree = $input->getArgument('treeFilePath');
        $list = $input->getArgument('listFilePath');
        $tree = json_decode(file_get_contents($tree), true);
        $list = json_decode(file_get_contents($list), true);
    }
}
