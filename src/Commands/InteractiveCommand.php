<?php

namespace JonathanTorres\Construct\Commands;

use JonathanTorres\Construct\Construct;
use JonathanTorres\Construct\Defaults;
use JonathanTorres\Construct\Helpers\Git;
use JonathanTorres\Construct\Helpers\Script;
use JonathanTorres\Construct\Helpers\Str;
use JonathanTorres\Construct\Settings;
use RuntimeException;
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
     * @var string
     */
    protected $construct;

    /**
     * String helper.
     *
     * @var JonathanTorres\Construct\Str
     */
    protected $str;

    /**
     * Construct settings.
     *
     * @var JonathanTorres\Construct\Settings
     */
    protected $settings;

    /**
     * Construct defaults.
     *
     * @var JonathanTorres\Construct\Defaults
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
     * @param JonathanTorres\Construct\Construct $construct
     * @param JonathanTorres\Construct\Str       $str
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
        $this->setName('generate:interactive');
        $this->setDescription('Generate a basic PHP project based on a series of questions.');
    }

    /**
     * Execute command.
     *
     * @param Symfony\Component\Console\Input\InputInterface  $input
     * @param Symfony\Component\Console\Input\OutputInterface $output
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
            'Which testing framework will you use? Default is "' . Defaults::TEST_FRAMEWORK . '"',
            $this->defaults->testingFrameworks,
            0
        );

        $licenseQuestion = new ChoiceQuestion(
            'Which open source license will your project use? Default is "' . Defaults::LICENSE . '"',
            $this->defaults->licenses,
            0
        );

        $phpVersionQuestion = new ChoiceQuestion(
            'What\'s the minimum required php version for this project? Default is "' . $this->systemPhpVersion . '"',
            $this->defaults->phpVersions,
            2
        );

        $namespaceQuestion = new Question('What will be the namespace for the project? Default is "Vendor\Project"', Defaults::PROJECT_NAMESPACE);
        $gitQuestion = new ConfirmationQuestion('Do you want to initialize a local git repository?', false);
        $phpCsQuestion = new ConfirmationQuestion('Do you want to generate a PHP Coding Standards Fixer configuration?', false);
        $composerKeywordsQuestion = new Question('Supply a comma separated list of keywords for you composer.json (Optional) ', '');
        $vagrantFileQuestion = new ConfirmationQuestion('Do you want to generate a Vagrantfile?', false);
        $editorConfigQuestion = new ConfirmationQuestion('Do you want to generate a generate an EditorConfig configuration?', false);
        $environmentFileQuestion = new ConfirmationQuestion('Do you want to generate an .env file?', false);
        $lgtmFileQuestion = new ConfirmationQuestion('Do you want to generate an LGTM configuration file?', false);
        $githubTemplatesQuestion = new ConfirmationQuestion('Do you want to generate GitHub templates?', false);
        $codeOfConductQuestion = new ConfirmationQuestion('Do you want to add a Code of Conduct file?', false);

        $projectName = $helper->ask($input, $output, $projectNameQuestion);
        $testingFramework = $helper->ask($input, $output, $testingFrameworkQuestion);
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
        $codeOfConduct = $helper->ask($input, $output, $codeOfConductQuestion);

        $this->settings = new Settings(
            $projectName,
            $testingFramework,
            $license,
            $namespace,
            $git,
            $phpCs,
            $composerKeywords,
            $vagrantFile,
            $editorConfig,
            $phpVersion,
            $environmentFile,
            $lgtmFile,
            $githubTemplates,
            $codeOfConduct
        );

        $output->writeln('Creating your project...');
        $this->construct->generate($this->settings, new Git, new Script);
        $output->writeln('<info>Project "' . $projectName . '" constructed.</info>');
    }
}
