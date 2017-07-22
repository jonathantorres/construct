<?php

namespace Construct\Commands;

use Construct\Configuration;
use Construct\Construct;
use Construct\Defaults;
use Construct\Helpers\Filesystem;
use Construct\Helpers\Git;
use Construct\Helpers\Script;
use Construct\Helpers\Str;
use Construct\Settings;
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
     * @var \Construct\Str
     */
    protected $str;

    /**
     * Filesystem helper.
     *
     * @var \Construct\Helpers\Filesystem
     */
    protected $filesystem;

    /**
     * Construct settings.
     *
     * @var \Construct\Settings
     */
    protected $settings;

    /**
     * Construct defaults.
     *
     * @var \Construct\Defaults
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
     * @param \Construct\Construct          $construct
     * @param \Construct\Str                $str
     * @param \Construct\Helpers\Filesystem $filesystem
     *
     * @return void
     */
    public function __construct(Construct $construct, Str $str, Filesystem $filesystem)
    {
        $this->construct = $construct;
        $this->str = $str;
        $this->filesystem = $filesystem;
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
        $testFrameworkDescription = 'Testing framework (one of: ' . join(', ', $this->defaults->testingFrameworks) . ')';
        $cliFrameworkDescription = 'CLI framework';
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
        $githubDocsDescription = 'Generate GitHub docs';
        $githubDescription = 'Generate GitHub templates and docs';
        $codeOfConductDescription = 'Generate Code of Conduct file';
        $configurationDescription = 'Generate from configuration file';
        $ignoreDefaultConfigurationDescription = 'Ignore present default configuration file';
        $configurationDefault = $this->filesystem->getDefaultConfigurationFile();

        $this->setName('generate');
        $this->setDescription('Generates a basic PHP project/micro-package');
        $this->addArgument('name', InputArgument::REQUIRED, $nameDescription);
        $this->addOption('test', 't', InputOption::VALUE_OPTIONAL, $testFrameworkDescription, Defaults::TEST_FRAMEWORK);
        $this->addOption('test-framework', null, InputOption::VALUE_OPTIONAL, $testFrameworkDescription, Defaults::TEST_FRAMEWORK);
        $this->addOption('license', 'l', InputOption::VALUE_OPTIONAL, $licenseDescription, Defaults::LICENSE);
        $this->addOption('namespace', 's', InputOption::VALUE_OPTIONAL, $namespaceDescription, Defaults::PROJECT_NAMESPACE);
        $this->addOption('git', 'g', InputOption::VALUE_NONE, $gitDescription);
        $this->addOption('phpcs', 'p', InputOption::VALUE_NONE, $phpcsDescription);
        $this->addOption('keywords', 'k', InputOption::VALUE_OPTIONAL, $keywordsDescription);
        $this->addOption('vagrant', null, InputOption::VALUE_NONE, $vagrantDescription);
        $this->addOption('editor-config', 'e', InputOption::VALUE_NONE, $editorConfigDescription);
        $this->addOption('php', null, InputOption::VALUE_OPTIONAL, $phpVersionDescription, $this->systemPhpVersion);
        $this->addOption('env', null, InputOption::VALUE_NONE, $environmentDescription);
        $this->addOption('lgtm', null, InputOption::VALUE_NONE, $lgtmDescription);
        $this->addOption('github', null, InputOption::VALUE_NONE, $githubDescription);
        $this->addOption('github-templates', null, InputOption::VALUE_NONE, $githubTemplatesDescription);
        $this->addOption('github-docs', null, InputOption::VALUE_NONE, $githubDocsDescription);
        $this->addOption('code-of-conduct', null, InputOption::VALUE_NONE, $codeOfConductDescription);
        $this->addOption('config', 'c', InputOption::VALUE_OPTIONAL, $configurationDescription, $configurationDefault);
        $this->addOption('ignore-default-config', 'i', InputOption::VALUE_NONE, $ignoreDefaultConfigurationDescription);
        $this->addOption('cli-framework', null, InputOption::VALUE_OPTIONAL, $cliFrameworkDescription, Defaults::CLI_FRAMEWORK);
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
        $cliFramework = null;

        if ($input->hasParameterOption('--cli-framework')) {
            $cliFramework = $input->getOption('cli-framework');

            if (!$this->str->isValid($cliFramework)) {
                $warningMessage = '<error>Warning: "' . $cliFramework . '" is not '
                    . 'a valid Composer package name, please use "vendor/project"</error>';
                $output->writeln($warningMessage);

                return false;
            }
        }

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

        // alias for --test-framework
        if ($testingFramework !== Defaults::TEST_FRAMEWORK) {
            $testFramework = $testingFramework;
        }

        if ($this->isConfigurationApplicable($configuration)
            && $ignoreDefaultConfiguration === false) {
            $this->settings = Configuration::getSettings(
                $configuration,
                $projectName,
                $keywords,
                $this->filesystem
            );

            if ($cliFramework) {
                $this->settings->setCliFramework($cliFramework);
            }
        } else {
            if ($github) {
                $githubTemplates = $githubDocs = true;
            }

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
                $githubTemplates,
                $codeOfConduct,
                $githubDocs,
                $cliFramework
            );
        }

        if (!$this->str->isValid($projectName)) {
            $warningMessage = '<error>Warning: "' . $projectName . '" is not '
                . 'a valid project name, please use "vendor/project"</error>';
            $output->writeln($warningMessage);

            return false;
        }

        $this->warnAndOverwriteInvalidSettingsWithDefaults($output);

        $this->construct->generate($this->settings, new Git, new Script);

        $this->initializedGitMessage($output);
        $this->bootstrappedCodeceptionMessage($testFramework, $output);
        $this->initializedBehatMessage($testFramework, $output);

        $output->writeln('<info>Project "' . $projectName . '" constructed.</info>');
    }

    /**
     * Shows warnings and sets a new settings which overwrites
     * invalid settings with default values.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    private function warnAndOverwriteInvalidSettingsWithDefaults($output)
    {
        $this->projectNameContainsPhpWarning($output);

        $license = $this->supportedLicenseWarning($output);
        $testFramework = $this->testFrameworkWarning($output);
        $phpVersion = $this->phpVersionWarning($output);

        $this->settings = new Settings(
            $this->settings->getProjectName(),
            $testFramework,
            $license,
            $this->settings->getNamespace(),
            $this->settings->withGitInit(),
            $this->settings->withPhpcsConfiguration(),
            $this->settings->getComposerKeywords(),
            $this->settings->withVagrantfile(),
            $this->settings->withEditorConfig(),
            $phpVersion,
            $this->settings->withEnvironmentFiles(),
            $this->settings->withLgtmConfiguration(),
            $this->settings->withGithubTemplates(),
            $this->settings->withCodeOfConduct(),
            $this->settings->withGithubDocs(),
            $this->settings->getCliFramework()
        );
    }

    /**
     * Determine if a configuration is applicable.
     *
     * @param  string  The default or a command line provided configuration file.
     * @return boolean
     */
    private function isConfigurationApplicable($configuration)
    {
        if ($configuration === $this->filesystem->getDefaultConfigurationFile()
            && $this->filesystem->hasDefaultConfigurationFile()) {
            return true;
        }

        if ($configuration !== $this->filesystem->getDefaultConfigurationFile()) {
            return true;
        }

        return false;
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

        if (!in_array($license, $this->defaults->licenses)) {
            $warning = '<error>Warning: "' . $license . '" is not a supported license. '
                . 'Using ' . Defaults::LICENSE . '.</error>';
            $output->writeln($warning);
            $license = Defaults::LICENSE;
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

        if (!in_array($testFramework, $this->defaults->testingFrameworks)) {
            $warning = '<error>Warning: "' . $testFramework . '" is not a supported testing framework. '
                . 'Using ' . Defaults::TEST_FRAMEWORK . '.</error>';
            $output->writeln($warning);
            $testFramework = Defaults::TEST_FRAMEWORK;
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
