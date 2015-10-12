<?php

namespace JonathanTorres\Construct\Commands;

use JonathanTorres\Construct\Construct;
use JonathanTorres\Construct\Helpers\Git;
use JonathanTorres\Construct\Helpers\Script;
use JonathanTorres\Construct\Helpers\Str;
use JonathanTorres\Construct\Settings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConstructCommand extends Command
{
    /**
     * The construct implementation.
     *
     * @var string
     */
    protected $construct;

    /**
     * String helper.
     *
     * @var \JonathanTorres\Construct\Str
     */
    protected $str;

    /**
     * The available open source licenses. (more: http://choosealicense.com/licenses)
     *
     * @var array
     */
    protected $licenses = ['MIT', 'Apache-2.0', 'GPL-2.0', 'GPL-3.0'];

    /**
     * The available testing frameworks.
     *
     * @var array
     */
    protected $testingFrameworks = ['phpunit', 'behat', 'phpspec', 'codeception'];

    /**
     * Available php versions.
     *
     * @var array
     */
    protected $phpVersions = ['5.4.0', '5.5.0', '5.6.0'];

    /**
     * Initialize.
     *
     * @param \JonathanTorres\Construct\Construct $construct
     * @param \JonathanTorres\Construct\Str       $str
     *
     * @return void
     */
    public function __construct(Construct $construct, Str $str)
    {
        parent::__construct();

        $this->construct = $construct;
        $this->str = $str;
    }

    /**
     * Command configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $nameDescription = 'The vendor/project name';
        $testDescription = 'Testing framework (one of: ' . join(', ', $this->testingFrameworks) . ')';
        $licenseDescription = 'License (one of: ' . join(', ', $this->licenses) . ')';
        $namespaceDescription = 'Namespace for project';
        $gitDescription = 'Initialize an empty Git repo';
        $phpcsDescription = 'Generate a PHP Coding Standards Fixer configuration';
        $keywordsDescription = 'Comma separated list of Composer keywords';
        $vagrantDescription = 'Generate a Vagrantfile';
        $editorConfigDescription = 'Generate an EditorConfig configuration';
        $phpVersionDescription = 'Project minimun required php version';

        $this->setName('generate');
        $this->setDescription('Generates a basic PHP project');
        $this->addArgument('name', InputArgument::REQUIRED, $nameDescription);
        $this->addOption('test', 't', InputOption::VALUE_OPTIONAL, $testDescription, 'phpunit');
        $this->addOption('license', 'l', InputOption::VALUE_OPTIONAL, $licenseDescription, 'MIT');
        $this->addOption('namespace', 's', InputOption::VALUE_OPTIONAL, $namespaceDescription, 'Vendor\Project');
        $this->addOption('git', 'g', InputOption::VALUE_NONE, $gitDescription);
        $this->addOption('phpcs', 'p', InputOption::VALUE_NONE, $phpcsDescription);
        $this->addOption('keywords', 'k', InputOption::VALUE_OPTIONAL, $keywordsDescription);
        $this->addOption('vagrant', null, InputOption::VALUE_NONE, $vagrantDescription);
        $this->addOption('editor-config', 'e', InputOption::VALUE_NONE, $editorConfigDescription);
        $this->addOption('php', null, InputOption::VALUE_OPTIONAL, $phpVersionDescription, '5.6.0');
    }

    /**
     * Execute command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectName = $input->getArgument('name');
        $testFramework = $input->getOption('test');
        $license = $input->getOption('license');
        $namespace = $input->getOption('namespace');
        $git = $input->getOption('git');
        $phpcs = $input->getOption('phpcs');
        $keywords = $input->getOption('keywords');
        $vagrant = $input->getOption('vagrant');
        $editorConfig = $input->getOption('editor-config');
        $phpVersion = $input->getOption('php');

        if (!$this->str->isValid($projectName)) {
            $output->writeln('<error>Warning: "' . $projectName . '" is not a valid project name, please use "vendor/project"</error>');

            return false;
        }

        if ($this->str->contains($projectName, 'php')) {
            $containsPhpWarning = 'Warning: If you are about to create a micro-package "'
                . $projectName . '" should optimally not contain a "php" notation in the project name.';
            $output->writeln('<error>' . $containsPhpWarning . '</error>');
        }

        if (!in_array($license, $this->licenses)) {
            $output->writeln('<error>Warning: "' . $license . '" is not a supported license. Using MIT.</error>');
            $license = 'MIT';
        }

        if (!in_array($testFramework, $this->testingFrameworks)) {
            $output->writeln('<error>Warning: "' . $testFramework . '" is not a supported testing framework. Using phpunit.</error>');
            $testFramework = 'phpunit';
        }

        if (!in_array($phpVersion, $this->phpVersions)) {
            $output->writeln('<error>Warning: "'. $phpVersion . '" is not a supported php version. Using version 5.6.0</error>');
            $phpVersion = '5.6.0';
        }

        $settings = new Settings(
          $projectName,
          $testFramework,
          $license,
          $namespace,
          $git,
          $phpcs,
          $keywords,
          $vagrant,
          $editorConfig,
          $phpVersion
        );

        $this->construct->generate($settings, new Git, new Script);

        if ($settings->withGitInit()) {
            $folder = $this->construct->getprojectLower();
            $output->writeln('<info>Initialized git repo in "' . $folder . '".</info>');
        }

        if ($testFramework === 'codeception') {
            $output->writeln('<info>Bootstrapped codeception.</info>');
        }

        if ($testFramework === 'behat') {
            $output->writeln('<info>Initialized behat.</info>');
        }

        $output->writeln('<info>Project "' . $projectName . '" constructed.</info>');
    }
}
