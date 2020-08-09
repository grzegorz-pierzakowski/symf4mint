<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

final class TreeCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function configure(): void
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
        $this->process($tree, $list);
        $out = $input->getArgument('resultFilePath') ?: $this->container->getParameter('kernel.project_dir') . '/var/out.json';
        file_put_contents($out, json_encode($tree));
    }

    private function process(array &$tree, array $list): void
    {
        foreach ($tree as $node) {
            if (isset($node['children'])) {
                $this->process($node['children']);
            } else {
                //TODO to miało coś dpisać
            }
        }
    }
}
