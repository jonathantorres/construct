<?php namespace JonathanTorres\Construct\Commands\Settings;

class Construct
{

    /**
     * @var string
     */
    private $projectName;

    /**
     * @var string
     */
    private $testingFramework;

    /**
     * @var string
     */
    private $license;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var boolean
     */
    private $gitInit;

    /**
     * @var boolean
     */
    private $phpcsConfiguration;

    /**
     * @param string  $projectName        The entered project name.
     * @param string  $testingFramework   The entered testing framework.
     * @param string  $license            The entered project license.
     * @param string  $namespace          The entered namespace.
     * @param boolean $gitInit            Initialize a git repo?
     * @param boolean $phpcsConfiguration Generate a PHP Coding Standards Fixer configuration?
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
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @return string
     */
    public function getTestingFramework()
    {
        return $this->testingFramework;
    }

    /**
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return boolean
     */
    public function withGitInit()
    {
        return $this->gitInit;
    }

    /**
     * @return boolean
     */
    public function withPhpcsConfiguration()
    {
        return $this->phpcsConfiguration;
    }
}
