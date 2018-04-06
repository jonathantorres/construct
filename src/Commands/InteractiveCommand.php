<?php

declare(strict_types = 1);

namespace Construct\Commands;

use Construct\Construct;
use RuntimeException;
use Construct\Exceptions\ProjectDirectoryToBeAlreadyExists;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class InteractiveCommand extends Command
{
    /**
     * The construct implementation.
     *
     * @var \Construct\Construct
     */
    protected $construct;

    /**
     * String helper.
     *
     * @var \Construct\Helpers\Str
     */
    protected $str;

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
     * Initialize.
     *
     * @param \Construct\Construct $construct
     *
     * @return void
     */
    public function __construct(Construct $construct)
    {
        $this->construct = $construct;
        $this->str = $construct->getContainer()->get('Construct\Helpers\Str');
        $this->defaults = $construct->getContainer()->get('Construct\Defaults');
        $this->settings = $construct->getContainer()->get('Construct\Settings');

        parent::__construct();
    }

    /**
     * Command configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('generate:interactive');
        $this->setDescription('Generate a basic PHP project/micro-package based on a series of questions.');
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
        $helper = $this->getHelper('question');
        $projectNameQuestion = new Question('What\'s the name of your project? (Format: vendor/project) ');
        $projectNameQuestion->setValidator(function ($answer) {
            if (!$this->str->isValid($answer)) {
                throw new RuntimeException('Error: "' . $answer . '" is not a valid project name, please use "vendor/project"');
            }

            return $answer;
        });

        $testingFrameworkQuestion = new ChoiceQuestion(
            'Which testing framework will you use? Default is "' . $this->defaults->getTestingFramework() . '"',
            $this->defaults->getTestingFrameworks(),
            0
        );

        $cliProjectQuestion = new ConfirmationQuestion(
            'Do you want to create a CLI project?',
            false
        );

        $cliFrameworkQuestion = new Question(
            'Which CLI Framework will you use? Default is "' . $this->defaults->getCliFramework() . '"',
            $this->defaults->getCliFramework()
        );

        $cliFrameworkQuestion->setValidator(function ($answer) {
            if (!$this->str->isValid($answer)) {
                $exceptionMessage = 'Error: "' . $answer . '" is not a '
                    . 'valid Composer package name, please use "vendor/project"';
                throw new RuntimeException($exceptionMessage);
            }

            return $answer;
        });

        $licenseQuestion = new ChoiceQuestion(
            'Which open source license will your project use? Default is "' . $this->defaults->getLicense() . '"',
            $this->defaults->getLicenses(),
            0
        );

        $phpVersionQuestion = new ChoiceQuestion(
            'What\'s the minimum required php version for this project? Default is "' . $this->defaults->getSystemPhpVersion() . '"',
            $this->defaults->getPhpVersions(),
            2
        );

        $namespaceQuestion = new Question('What will be the namespace for the project? Default is "Vendor\Project"', $this->defaults->getProjectNamespace());
        $gitQuestion = new ConfirmationQuestion('Do you want to initialize a local git repository?', false);
        $phpCsQuestion = new ConfirmationQuestion('Do you want to generate a PHP Coding Standards Fixer configuration?', false);
        $composerKeywordsQuestion = new Question('Supply a comma separated list of keywords for you composer.json (Optional) ', '');
        $vagrantFileQuestion = new ConfirmationQuestion('Do you want to generate a Vagrantfile?', false);
        $editorConfigQuestion = new ConfirmationQuestion('Do you want to generate a generate an EditorConfig configuration?', false);
        $environmentFileQuestion = new ConfirmationQuestion('Do you want to generate an .env file?', false);
        $lgtmFileQuestion = new ConfirmationQuestion('Do you want to generate an LGTM configuration file?', false);
        $githubTemplatesQuestion = new ConfirmationQuestion('Do you want to generate GitHub templates?', false);
        $githubDocsQuestion = new ConfirmationQuestion('Do you want to generate GitHub docs?', false);
        $codeOfConductQuestion = new ConfirmationQuestion('Do you want to add a Code of Conduct file?', false);

        $projectName = $helper->ask($input, $output, $projectNameQuestion);
        $testingFramework = $helper->ask($input, $output, $testingFrameworkQuestion);
        $cliProject = $helper->ask($input, $output, $cliProjectQuestion);
        $cliFramework = null;

        if ($cliProject) {
            $cliFramework = $helper->ask($input, $output, $cliFrameworkQuestion);
        }

        $license = $helper->ask($input, $output, $licenseQuestion);
        $phpVersion = $helper->ask($input, $output, $phpVersionQuestion);
        $namespace = $helper->ask($input, $output, $namespaceQuestion);
        $git = $helper->ask($input, $output, $gitQuestion);
        $phpCs = $helper->ask($input, $output, $phpCsQuestion);
        $composerKeywords = $helper->ask($input, $output, $composerKeywordsQuestion);
        $vagrantFile = $helper->ask($input, $output, $vagrantFileQuestion);
        $editorConfig = $helper->ask($input, $output, $editorConfigQuestion);
        $environmentFile = $helper->ask($input, $output, $environmentFileQuestion);
        $lgtmFile = $helper->ask($input, $output, $lgtmFileQuestion);
        $githubTemplates = $helper->ask($input, $output, $githubTemplatesQuestion);
        $githubDocs = $helper->ask($input, $output, $githubDocsQuestion);
        $codeOfConduct = $helper->ask($input, $output, $codeOfConductQuestion);

        $this->settings->setProjectName($projectName);
        $this->settings->setTestingFramework($testingFramework);
        $this->settings->setLicense($license);
        $this->settings->setNamespace($namespace);
        $this->settings->setGitInit($git);
        $this->settings->setPhpcsConfiguration($phpCs);
        $this->settings->setComposerKeywords($composerKeywords);
        $this->settings->setVagrantfile($vagrantFile);
        $this->settings->setEditorConfig($editorConfig);
        $this->settings->setPhpVersion($phpVersion);
        $this->settings->setEnvironmentFiles($environmentFile);
        $this->settings->setLgtmConfiguration($lgtmFile);
        $this->settings->setGithubTemplates($githubTemplates);
        $this->settings->setGithubDocs($githubDocs);
        $this->settings->setCodeOfConduct($codeOfConduct);
        $this->settings->setCliFramework($cliFramework);

        $output->writeln('Creating your project...');

        try {
            $this->construct->generate();
        } catch (ProjectDirectoryToBeAlreadyExists $e) {
            $warningMessage = '<error>Warning: "' . $projectName . '" would be '
                . 'constructed into existing directory "' . $this->settings->getProjectLower() . '". '
                . 'Aborting further construction.</error>';
            $output->writeln($warningMessage);

            return false;
        }

        $output->writeln('<info>Project "' . $projectName . '" constructed.</info>');
    }
}
