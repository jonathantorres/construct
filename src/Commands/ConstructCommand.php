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
     * Initialize.
     *
     * @param \Illuminate\Filesystem\Filesystem $file
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
        $this->setName('generate');
        $this->setDescription('Generate a basic PHP project.');
        $this->addArgument('name', InputArgument::REQUIRED, 'The vendor/project name.');
        $this->addOption('test', 't', InputOption::VALUE_OPTIONAL, 'Testing framework', 'phpunit');
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

        if (!$this->str->isValid($this->projectName)) {
            $output->writeln('"' . $this->projectName . '" is not a valid project name, please use "vendor/project"');
            return false;
        }

        $warning = $this->construct->generate($this->projectName, $this->testing);

        if ($warning) {
            $output->writeln('Warning: Testing framework "' . $this->testing . '" does not exists. Using phpunit instead.');
        }

        $output->writeln('Project "' . $this->projectName . '" created.');
    }
}
