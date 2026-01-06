<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\UnicodeString;

#[AsCommand(    
    name: 'make:api-standard',
    description: 'Generates a standard API resource from an existing entity'
)]
class MakeApiResourceCommand extends Command
{

    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Entity (e.g. User)')
            ->addArgument('project-dir', InputArgument::OPTIONAL, 'The path to the target project (default: current directory)', '.')
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {

        $name = '/' . ltrim($input->getArgument('name'), '/');

        $projectDir = $this->kernel->getProjectDir();;
        $entityPath = "$projectDir/src/Entity/$name.php";

        $filesystem = new Filesystem();

        if (!$filesystem->exists($entityPath)) {
            $output->writeln("<error>Entity $name does not exist in $projectDir/src/Entity/</error>");
            return Command::FAILURE;
        }

        // Create directories if they do not exist
        $dirs = [
            "$projectDir/src/Api",
            "$projectDir/src/DTO",
            "$projectDir/src/Permission",
            "$projectDir/src/Resource"
        ];

        foreach ($dirs as $dir) {
            if (!$filesystem->exists($dir)) {
                $filesystem->mkdir($dir);
                $output->writeln("<info>Created directory $dir</info>");
            }
        }

        // Generate files from templates
        $this->generateFile($filesystem, $projectDir, $name, 'Controller');
        $this->generateFile($filesystem, $projectDir, $name, 'Input');
        $this->generateFile($filesystem, $projectDir, $name, 'Output');
        $this->generateFile($filesystem, $projectDir, $name, 'PermissionQuery');
        $this->generateFile($filesystem, $projectDir, $name, 'Resource');

        $output->writeln("<info>✔ Resource $name generated successfully in $projectDir!</info>");
        return Command::SUCCESS;
    }

    private function generateFile(
        Filesystem $filesystem,
        string $projectDir,
        string $name,
        string $type
    ): void {

        $templatesDir = __DIR__ . '/../Templates/Api';

        $entity = str_replace('/', '\\', $name);
        $parts = explode('/', $name);
        $basename = array_pop($parts);
        $namespace = $parts ? implode('\\', $parts) : '';

        dump($entity);
        dump($basename);
        dump($namespace);

        $templateMap = [
            'Controller' => "$templatesDir/Controller/Controller.tpl.php",
            'Input' => "$templatesDir/DTO/Input.tpl.php",
            'Output' => "$templatesDir/DTO/Output.tpl.php",
            'PermissionQuery' => "$templatesDir/Permission/PermissionQuery.tpl.php",
            'Resource' => "$templatesDir/Resource/Resource.tpl.php",
        ];

        $fileMap = [
            'Controller' => "$projectDir/src/Api/{$name}Controller.php",
            'Input' => "$projectDir/src/DTO/{$name}Input.php",
            'Output' => "$projectDir/src/DTO/{$name}Output.php",
            'PermissionQuery' => "$projectDir/src/Permission/{$name}PermissionQuery.php",
            'Resource' => "$projectDir/src/Resource/{$name}Resource.php",
        ];

        $content = file_get_contents($templateMap[$type]);
        $content = str_replace('{{NAMESPACE}}', $namespace, $content);
        $content = str_replace('{{NAME}}', $basename, $content);

        if($type == 'Controller') {
            $route = (new UnicodeString($basename))->camel()->snake()->replace('_', '-')->lower()->toString();

            $content = str_replace('{{ROUTE}}', $route, $content);
            $content = str_replace('{{ENTITY}}', $entity, $content);
        }

        $filesystem->dumpFile($fileMap[$type], $content);
    }
}
