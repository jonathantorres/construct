<?php

namespace JonathanTorres\Construct\Commands;

use JonathanTorres\Construct\Construct;
use JonathanTorres\Construct\Defaults;
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
     * Construct settings.
     *
     * @var \JonathanTorres\Construct\Settings
     */
    protected $settings;

    /**
     * Construct defaults.
     *
     * @var \JonathanTorres\Construct\Defaults
     */
    protected $defaults;

    /**
     * Php version currently used on the system.
     *
     * @var string
     */
    protected $systemPhpVersion;

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
        $this->construct = $construct;
        $this->str = $str;
        $this->defaults = new Defaults();
        $this->systemPhpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $nameDescription = 'The vendor/project name';
        $testDescription = 'Testing framework (one of: ' . join(', ', $this->defaults->testingFrameworks) . ')';
        $licenseDescription = 'License (one of: ' . join(', ', $this->defaults->licenses) . ')';
        $namespaceDescription = 'Namespace for project';
        $gitDescription = 'Initialize an empty Git repo';
        $phpcsDescription = 'Generate a PHP Coding Standards Fixer configuration';
        $keywordsDescription = 'Comma separated list of Composer keywords';
        $vagrantDescription = 'Generate a Vagrantfile';
        $editorConfigDescription = 'Generate an EditorConfig configuration';
        $phpVersionDescription = 'Project minimun required php version (one of: ' . join(', ', $this->defaults->phpVersions) . ')';
        $environmentDescription = 'Generate .env environment files';
        $lgtmDescription = 'Generate LGTM configuration files';
        $githubTemplatesDescription = 'Generate GitHub templates';

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
        $this->addOption('php', null, InputOption::VALUE_OPTIONAL, $phpVersionDescription, $this->systemPhpVersion);
        $this->addOption('env', null, InputOption::VALUE_NONE, $environmentDescription);
        $this->addOption('lgtm', null, InputOption::VALUE_NONE, $lgtmDescription);
        $this->addOption('github-templates', null, InputOption::VALUE_NONE, $githubTemplatesDescription);
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
        $environment = $input->getOption('env');
        $lgtm = $input->getOption('lgtm');
        $githubTemplates = $input->getOption('github-templates');

        if (!$this->str->isValid($projectName)) {
            $output->writeln('<error>Warning: "' . $projectName . '" is not a valid project name, please use "vendor/project"</error>');

            return false;
        }

        $this->containsPhpWarning($projectName, $output);
        $license = $this->supportedLicenseWarning($license, $output);
        $testFramework = $this->testFrameworkWarning($testFramework, $output);
        $phpVersion = $this->phpVersionWarning($phpVersion, $output);

        $this->settings = new Settings(
          $projectName,
          $testFramework,
          $license,
          $namespace,
          $git,
          $phpcs,
          $keywords,
          $vagrant,
          $editorConfig,
          $phpVersion,
          $environment,
          $lgtm,
          $githubTemplates
        );

        $this->construct->generate($this->settings, new Git, new Script);

        $this->initializedGitMessage($output);
        $this->bootstrappedCodeceptionMessage($testFramework, $output);
        $this->initializedBehatMessage($testFramework, $output);

        $output->writeln('<info>Project "' . $projectName . '" constructed.</info>');
    }

    /**
     * Show warning if the project name contains the string "php"
     *
     * @param string                                            $projectName
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function containsPhpWarning($projectName, $output)
    {
        if ($this->str->contains($projectName, 'php')) {
            $containsPhpWarning = 'Warning: If you are about to create a micro-package "'
                . $projectName . '" should optimally not contain a "php" notation in the project name.';
            $output->writeln('<error>' . $containsPhpWarning . '</error>');
        }
    }

    /**
     * Show warning if a license that is not supported is specified.
     *
     * @param string                                            $license
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string
     */
    private function supportedLicenseWarning($license, $output)
    {
        if (!in_array($license, $this->defaults->licenses)) {
            $output->writeln('<error>Warning: "' . $license . '" is not a supported license. Using MIT.</error>');

            $license = 'MIT';
        }

        return $license;
    }

    /**
     * Show warning if a test framework that is not supported is specified.
     *
     * @param string                                            $testFramework
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string
     */
    private function testFrameworkWarning($testFramework, $output)
    {
        if (!in_array($testFramework, $this->defaults->testingFrameworks)) {
            $output->writeln('<error>Warning: "' . $testFramework . '" is not a supported testing framework. Using phpunit.</error>');
            $testFramework = 'phpunit';
        }

        return $testFramework;
    }

    /**
     * Show warning if an invalid php version or
     * a version greater than the one on the system is specified.
     *
     * @param string                                            $phpVersion
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string
     */
    private function phpVersionWarning($phpVersion, $output)
    {
        if (!$this->str->phpVersionIsValid($phpVersion)) {
            $output->writeln('<error>Warning: "' . $phpVersion . '" is not a valid php version. Using version ' . $this->systemPhpVersion . '</error>');
            $phpVersion = $this->systemPhpVersion;
        }

        if (version_compare($phpVersion, $this->systemPhpVersion, '>')) {
            $output->writeln('<error>Warning: "' . $phpVersion . '" is greater than your installed php version. Using version ' . $this->systemPhpVersion . '</error>');
            $phpVersion = $this->systemPhpVersion;
        }

        return $phpVersion;
    }

    /**
     * Show message if an empty git repo is initialized.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function initializedGitMessage($output)
    {
        if ($this->settings->withGitInit()) {
            $folder = $this->construct->getprojectLower();
            $output->writeln('<info>Initialized git repo in "' . $folder . '".</info>');
        }
    }

    /**
     * Show message if codeception is bootstrapped successfully.
     *
     * @param string                                            $testFramework
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function bootstrappedCodeceptionMessage($testFramework, $output)
    {
        if ($testFramework === 'codeception') {
            $output->writeln('<info>Bootstrapped codeception.</info>');
        }
    }

    /**
     * Show message if behat is initialized successfully.
     *
     * @param string                                            $testFramework
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function initializedBehatMessage($testFramework, $output)
    {
        if ($testFramework === 'behat') {
            $output->writeln('<info>Initialized behat.</info>');
        }
    }
}
