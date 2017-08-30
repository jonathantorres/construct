<?php

namespace Construct\Commands;

use Construct\Construct;
use Construct\Constructors\Cli;
use Construct\Constructors\CodeOfConduct;
use Construct\Constructors\Composer;
use Construct\Constructors\Docs;
use Construct\Constructors\EditorConfig;
use Construct\Constructors\EnvironmentFiles;
use Construct\Constructors\GitAttributes;
use Construct\Constructors\GitHubDocs;
use Construct\Constructors\GitHubTemplates;
use Construct\Constructors\GitIgnore;
use Construct\Constructors\GitMessage;
use Construct\Constructors\LgtmFiles;
use Construct\Constructors\License;
use Construct\Constructors\PhpCs;
use Construct\Constructors\ProjectClass;
use Construct\Constructors\Src;
use Construct\Constructors\Tests;
use Construct\Constructors\Travis;
use Construct\Constructors\Vagrant;
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
    private $construct;

    /**
     * String helper.
     *
     * @var \Construct\Str
     */
    private $str;

    /**
     * Filesystem helper.
     *
     * @var \Construct\Helpers\Filesystem
     */
    private $filesystem;

    /**
     * Construct defaults.
     *
     * @var \Construct\Defaults
     */
    private $defaults;

    /**
     * Construct settings.
     *
     * @var \Construct\Settings
     */
    private $settings;

    /**
     * The currently loaded configuration.
     *
     * @var \Construct\Configuration
     */
    private $config;

    /**
     * Initialize main construct command.
     *
     * @param \Construct\Construct $construct
     *
     * @return void
     */
    public function __construct(Construct $construct)
    {
        $this->construct = $construct;
        $this->str = $construct->getContainer()->get('Construct\Helpers\Str');
        $this->filesystem = $construct->getContainer()->get('Construct\Helpers\Filesystem');
        $this->defaults = $construct->getContainer()->get('Construct\Defaults');
        $this->settings = $construct->getContainer()->get('Construct\Settings');
        $this->config = $construct->getContainer()->get('Construct\Configuration');

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
        $testFrameworkDescription = 'Testing framework (one of: ' . join(', ', $this->defaults->getTestingFrameworks()) . ')';
        $cliFrameworkDescription = 'CLI framework';
        $licenseDescription = 'License (one of: ' . join(', ', $this->defaults->getLicenses()) . ')';
        $namespaceDescription = 'Namespace for project';
        $gitDescription = 'Initialize an empty Git repo';
        $phpcsDescription = 'Generate a PHP Coding Standards Fixer configuration';
        $keywordsDescription = 'Comma separated list of Composer keywords';
        $vagrantDescription = 'Generate a Vagrantfile';
        $editorConfigDescription = 'Generate an EditorConfig configuration';
        $phpVersionDescription = 'Project minimun required php version (one of: ' . join(', ', $this->defaults->getPhpVersions()) . ')';
        $environmentDescription = 'Generate .env environment files';
        $lgtmDescription = 'Generate LGTM configuration files';
        $githubTemplatesDescription = 'Generate GitHub templates';
        $githubDocsDescription = 'Generate GitHub docs';
        $githubDescription = 'Generate GitHub templates and docs';
        $codeOfConductDescription = 'Generate Code of Conduct file';
        $configurationDescription = 'Generate from configuration file';
        $ignoreDefaultConfigurationDescription = 'Ignore present default configuration file';
        $configurationDefault = $this->filesystem->getDefaultConfigurationFile();

        $this->setName('generate');
        $this->setDescription('Generates a basic PHP project/micro-package');
        $this->addArgument('name', InputArgument::REQUIRED, $nameDescription);
        $this->addOption('test', 't', InputOption::VALUE_OPTIONAL, $testFrameworkDescription, $this->defaults->getTestingFramework());
        $this->addOption('test-framework', null, InputOption::VALUE_OPTIONAL, $testFrameworkDescription, $this->defaults->getTestingFramework());
        $this->addOption('license', 'l', InputOption::VALUE_OPTIONAL, $licenseDescription, $this->defaults->getLicense());
        $this->addOption('namespace', 's', InputOption::VALUE_OPTIONAL, $namespaceDescription, $this->defaults->getProjectNamespace());
        $this->addOption('git', 'g', InputOption::VALUE_NONE, $gitDescription);
        $this->addOption('phpcs', 'p', InputOption::VALUE_NONE, $phpcsDescription);
        $this->addOption('keywords', 'k', InputOption::VALUE_OPTIONAL, $keywordsDescription);
        $this->addOption('vagrant', null, InputOption::VALUE_NONE, $vagrantDescription);
        $this->addOption('editor-config', 'e', InputOption::VALUE_NONE, $editorConfigDescription);
        $this->addOption('php', null, InputOption::VALUE_OPTIONAL, $phpVersionDescription, $this->defaults->getSystemPhpVersion());
        $this->addOption('env', null, InputOption::VALUE_NONE, $environmentDescription);
        $this->addOption('lgtm', null, InputOption::VALUE_NONE, $lgtmDescription);
        $this->addOption('github', null, InputOption::VALUE_NONE, $githubDescription);
        $this->addOption('github-templates', null, InputOption::VALUE_NONE, $githubTemplatesDescription);
        $this->addOption('github-docs', null, InputOption::VALUE_NONE, $githubDocsDescription);
        $this->addOption('code-of-conduct', null, InputOption::VALUE_NONE, $codeOfConductDescription);
        $this->addOption('config', 'c', InputOption::VALUE_OPTIONAL, $configurationDescription, $configurationDefault);
        $this->addOption('ignore-default-config', 'i', InputOption::VALUE_NONE, $ignoreDefaultConfigurationDescription);
        $this->addOption('cli-framework', null, InputOption::VALUE_OPTIONAL, $cliFrameworkDescription, $this->defaults->getCliFramework());
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
        $testingFramework = $input->getOption('test-framework');
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
        $githubDocs = $input->getOption('github-docs');
        $github = $input->getOption('github');
        $codeOfConduct = $input->getOption('code-of-conduct');
        $ignoreDefaultConfiguration = $input->getOption('ignore-default-config');
        $configuration = $input->getOption('config');
        $cliFramework = null;

        // special case for cli-framework
        if ($input->hasParameterOption('--cli-framework')) {
            $cliFramework = $input->getOption('cli-framework');

            if (!$this->str->isValid($cliFramework)) {
                $warning = '<error>Warning: "' . $cliFramework . '" is not a valid Composer package name. Using "' . $this->defaults->getCliFramework() . '" instead.</error>';
                $output->writeln($warning);
                $cliFramework = $this->defaults->getCliFramework();
            }
        }

        // alias for --test-framework
        if ($testingFramework !== $this->defaults->getTestingFramework()) {
            $testFramework = $testingFramework;
        }

        // Used the --github option,
        // so GitHub templates and docs will be generated
        if ($github) {
            $githubTemplates = $githubDocs = true;
        }

        // set the initial project settings
        $this->settings->setProjectName($projectName);
        $this->settings->setTestingFramework($testFramework);
        $this->settings->setLicense($license);
        $this->settings->setNamespace($namespace);
        $this->settings->setGitInit($git);
        $this->settings->setPhpcsConfiguration($phpcs);
        $this->settings->setComposerKeywords($keywords);
        $this->settings->setVagrantfile($vagrant);
        $this->settings->setEditorConfig($editorConfig);
        $this->settings->setPhpVersion($phpVersion);
        $this->settings->setEnvironmentFiles($environment);
        $this->settings->setLgtmConfiguration($lgtm);
        $this->settings->setGithubTemplates($githubTemplates);
        $this->settings->setGithubDocs($githubDocs);
        $this->settings->setCodeOfConduct($codeOfConduct);
        $this->settings->setCliFramework($cliFramework);

        // using a .construct configuration file
        if ($this->config->isApplicable($configuration)
            && $ignoreDefaultConfiguration === false) {
            $newSettings = $this->config->overwriteSettings($this->settings, $configuration);

            $this->settings = $newSettings;
        }

        // warning message if the project name is invalid
        if (!$this->str->isValid($projectName)) {
            $warningMessage = '<error>Warning: "' . $projectName . '" is not '
                . 'a valid project name, please use "vendor/project"</error>';
            $output->writeln($warningMessage);

            return false;
        }

        $this->warnAndOverwriteInvalidSettingsWithDefaults($output);

         // add constructors here using the current settings (whether from input or from the configuration file)
        $this->construct->addConstructor(new Src($this->construct->getContainer()));
        $this->construct->addConstructor(new Docs($this->construct->getContainer()));
        $this->construct->addConstructor(new Tests($this->construct->getContainer()));
        $this->construct->addConstructor(new Cli($this->construct->getContainer()));
        $this->construct->addConstructor(new PhpCs($this->construct->getContainer()));
        $this->construct->addConstructor(new Vagrant($this->construct->getContainer()));
        $this->construct->addConstructor(new EditorConfig($this->construct->getContainer()));
        $this->construct->addConstructor(new EnvironmentFiles($this->construct->getContainer()));
        $this->construct->addConstructor(new LgtmFiles($this->construct->getContainer()));
        $this->construct->addConstructor(new GitHubTemplates($this->construct->getContainer()));
        $this->construct->addConstructor(new GitHubDocs($this->construct->getContainer()));
        $this->construct->addConstructor(new CodeOfConduct($this->construct->getContainer()));
        $this->construct->addConstructor(new Travis($this->construct->getContainer()));
        $this->construct->addConstructor(new License($this->construct->getContainer()));
        $this->construct->addConstructor(new Composer($this->construct->getContainer()));
        $this->construct->addConstructor(new ProjectClass($this->construct->getContainer()));
        $this->construct->addConstructor(new GitIgnore($this->construct->getContainer()));
        $this->construct->addConstructor(new GitMessage($this->construct->getContainer()));
        $this->construct->addConstructor(new GitAttributes($this->construct->getContainer()));

        $this->construct->generate();

        $this->initializedGitMessage($output);
        $this->bootstrappedCodeceptionMessage($output);
        $this->initializedBehatMessage($output);

        $output->writeln('<info>Project "' . $projectName . '" constructed.</info>');
    }

    /**
     * Shows warnings and sets new settings which overwrites
     * invalid settings with default values.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function warnAndOverwriteInvalidSettingsWithDefaults($output)
    {
        $this->projectNameContainsPhpWarning($output);

        $license = $this->supportedLicenseWarning($output);
        $testFramework = $this->testFrameworkWarning($output);
        $phpVersion = $this->phpVersionWarning($output);

        $this->settings->setLicense($license);
        $this->settings->setTestingFramework($testFramework);
        $this->settings->setPhpVersion($phpVersion);
    }

    /**
     * Show warning if the project name contains the string "php"
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function projectNameContainsPhpWarning($output)
    {
        $projectName = $this->settings->getProjectName();

        if ($this->str->contains($projectName, 'php')) {
            $containsPhpWarning = 'Warning: If you are about to create a micro-package "'
                . $projectName . '" should optimally not contain a "php" notation in the project name.';
            $output->writeln('<error>' . $containsPhpWarning . '</error>');
        }
    }

    /**
     * Show warning if a license that is not supported is specified.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string
     */
    private function supportedLicenseWarning($output)
    {
        $license = $this->settings->getLicense();

        if (!in_array($license, $this->defaults->getLicenses())) {
            $warning = '<error>Warning: "' . $license . '" is not a supported license. '
                . 'Using ' . $this->defaults->getLicense() . '.</error>';
            $output->writeln($warning);
            $license = $this->defaults->getLicense();
        }

        return $license;
    }

    /**
     * Show warning if a test framework that is not supported is specified.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string
     */
    private function testFrameworkWarning($output)
    {
        $testFramework = $this->settings->getTestingFramework();

        if (!in_array($testFramework, $this->defaults->getTestingFrameworks())) {
            $warning = '<error>Warning: "' . $testFramework . '" is not a supported testing framework. '
                . 'Using ' . $this->defaults->getTestingFramework() . '.</error>';
            $output->writeln($warning);
            $testFramework = $this->defaults->getTestingFramework();
        }

        return $testFramework;
    }

    /**
     * Show warning if an invalid php version or
     * a version greater than the one on the system is specified.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return string
     */
    private function phpVersionWarning($output)
    {
        $phpVersion = $this->settings->getPhpVersion();

        if (!$this->str->phpVersionIsValid($phpVersion)) {
            $output->writeln('<error>Warning: "' . $phpVersion . '" is not a valid php version. Using version ' . $this->defaults->getSystemPhpVersion() . '</error>');
            $phpVersion = $this->defaults->getSystemPhpVersion();
        }

        if (version_compare($phpVersion, $this->defaults->getSystemPhpVersion(), '>')) {
            $output->writeln('<error>Warning: "' . $phpVersion . '" is greater than your installed php version. Using version ' . $this->defaults->getSystemPhpVersion() . '</error>');
            $phpVersion = $this->defaults->getSystemPhpVersion();
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
            $folder = $this->settings->getProjectLower();
            $output->writeln('<info>Initialized git repo in "' . $folder . '".</info>');
        }
    }

    /**
     * Show message if codeception is bootstrapped successfully.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function bootstrappedCodeceptionMessage($output)
    {
        if ($this->settings->getTestingFramework() === 'codeception') {
            $output->writeln('<info>Bootstrapped codeception.</info>');
        }
    }

    /**
     * Show message if behat is initialized successfully.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    private function initializedBehatMessage($output)
    {
        if ($this->settings->getTestingFramework() === 'behat') {
            $output->writeln('<info>Initialized behat.</info>');
        }
    }
}
