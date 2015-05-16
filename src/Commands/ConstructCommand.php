<?php namespace JonathanTorres\Construct\Commands;

use Illuminate\Filesystem\Filesystem;
use JonathanTorres\Construct\Construct;
use JonathanTorres\Construct\Str;
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
     **/
    protected $construct;

    /**
     * String helper.
     *
     * @var \JonathanTorres\Construct\Str
     **/
    protected $str;

    /**
     * Entered project name.
     *
     * @var string
     **/
    protected $projectName;

    /**
     * The entered testing framework.
     *
     * @var string
     **/
    protected $testing;

    /**
     * The open source license.
     *
     * @var string
     **/
    protected $license;

    /**
     * The available open source licenses. (more: http://choosealicense.com/licenses)
     *
     * @var array
     **/
    protected $licenses = ['MIT', 'Apache-2.0', 'GPL-2.0', 'GPL-3.0'];

    /**
     * The available testing frameworks.
     *
     * @var array
     **/
    protected $testingFrameworks = ['phpunit', 'behat', 'phpspec', 'codeception'];

    /**
     * Initialize.
     *
     * @param \JonathanTorres\Construct\Construct $construct
     * @param \JonathanTorres\Construct\Str $str
     *
     * @return void
     **/
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
     **/
    protected function configure()
    {
        $licenseDescription = 'License (one of: ' . join(', ', $this->licenses) . ')';
        $testDescription = 'Testing framework (one of: ' . join(', ', $this->testingFrameworks) . ')';

        $this->setName('generate');
        $this->setDescription('Generate a basic PHP project');
        $this->addArgument('name', InputArgument::REQUIRED, 'The vendor/project name');
        $this->addOption('test', 't', InputOption::VALUE_OPTIONAL, $testDescription, 'phpunit');
        $this->addOption('license', 'l', InputOption::VALUE_OPTIONAL, $licenseDescription, 'MIT');
    }

    /**
     * Execute command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     **/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->projectName = $input->getArgument('name');
        $this->testing = $input->getOption('test');
        $this->license = $input->getOption('license');

        if (!$this->str->isValid($this->projectName)) {
            $output->writeln('<error>"' . $this->projectName . '" is not a valid project name, please use "vendor/project"</error>');
            return false;
        }

        if (!in_array($this->license, $this->licenses)) {
            $output->writeln('<error>Warning: "' . $this->license . '" is not a known license, yet. Using MIT by default.</error>');
            $this->license = 'MIT';
        }

        if (!in_array($this->testing, $this->testingFrameworks)) {
            $output->writeln('<error>Warning: "' . $this->testing . '" is not a known testing framework, yet. Using phpunit by default.</error>');
            $this->testing = 'phpunit';
        }

        $this->construct->generate($this->projectName, $this->testing, $this->license);

        $output->writeln('<info>Project "' . $this->projectName . '" constructed.</info>');
    }
}
