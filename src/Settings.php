<?php namespace JonathanTorres\Construct;

class Settings
{

    /**
     * The entered project name.
     *
     * @var string
     */
    private $projectName;

    /**
     * The entered testing framework.
     *
     * @var string
     */
    private $testingFramework;

    /**
     * The entered project license.
     *
     * @var string
     */
    private $license;

    /**
     * The entered namespace.
     *
     * @var string
     */
    private $namespace;

    /**
     * Initialize a git repo?
     *
     * @var boolean
     */
    private $gitInit;

    /**
     * Generate a PHP Coding Standards Fixer configuration?
     *
     * @var boolean
     */
    private $phpcsConfiguration;

    /**
     * Initialize.
     *
     * @param string  $projectName        The entered project name.
     * @param string  $testingFramework   The entered testing framework.
     * @param string  $license            The entered project license.
     * @param string  $namespace          The entered namespace.
     * @param boolean $gitInit            Initialize a git repo?
     * @param boolean $phpcsConfiguration Generate a PHP Coding Standards Fixer configuration?
     *
     * @return void
     */
    public function __construct(
        $projectName,
        $testingFramework,
        $license,
        $namespace,
        $gitInit,
        $phpcsConfiguration
    ) {
        $this->projectName = $projectName;
        $this->testingFramework = $testingFramework;
        $this->license = $license;
        $this->namespace = $namespace;
        $this->gitInit = $gitInit;
        $this->phpcsConfiguration = $phpcsConfiguration;
    }

    /**
     * Get the entered project name.
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Get the entered testing framework.
     *
     * @return string
     */
    public function getTestingFramework()
    {
        return $this->testingFramework;
    }

    /**
     * Get the entered license.
     *
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Get the entered namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Whether or not to initialize a git repo on the project.
     *
     * @return boolean
     */
    public function withGitInit()
    {
        return $this->gitInit;
    }

    /**
     * Whether or not to use phpcs on the project.
     *
     * @return boolean
     */
    public function withPhpcsConfiguration()
    {
        return $this->phpcsConfiguration;
    }
}
