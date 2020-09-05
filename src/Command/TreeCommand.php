<?php declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Filesystem\Filesystem;

final class TreeCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $names = [];

    protected function configure(): void
    {
        $targetDir = __DIR__ . '/../../var/input/';
        $this->setName('app:tree:fill')
            ->addArgument('treeFilePath', InputArgument::OPTIONAL, 'tree file', $targetDir . 'tree.json')
            ->addArgument('listFilePath', InputArgument::OPTIONAL, 'list file', $targetDir . 'list.json')
            ->addArgument('resultFilePath', InputArgument::OPTIONAL, 'outputFile', __DIR__ . '/../../var/output/list.json');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tree = $input->getArgument('treeFilePath');
        $list = $input->getArgument('listFilePath');
        $tree = json_decode(file_get_contents($tree), true);
        foreach (json_decode(file_get_contents($list), true) as $item) {
            $this->names[$item['category_id']] = $item['translations']['pl_PL']['name'];
        }
        $tree = $this->process($tree);
        $out = $input->getArgument('resultFilePath');
        $this->mkDir($out);
        file_put_contents($out, json_encode($tree));
        return 0;
    }

    private function process(array $tree): array
    {
        foreach ($tree as $key => $node) {
            if (array_key_exists($node['id'], $this->names)) {
                $tree[$key]['name'] = $this->names[$node['id']];
            }
            if (!empty($node['children'])) {
                $tree[$key]['children'] = $this->process($node['children']);
            }
        }
        return $tree;
    }

    private function mkDir(string $file): void
    {
        $parts = explode('/', $file);
        array_pop($parts);
        $directory = implode('/', $parts);
        if (file_exists($directory)) return;
        (new Filesystem())->mkdir($directory);
    }
}
