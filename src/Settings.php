<?php

namespace JonathanTorres\Construct;

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
     * The entered list of Composer keywords.
     *
     * @var string
     */
    private $composerKeywords;

    /**
     * Generate a Vagrantfile?
     *
     * @var boolean
     */
    private $vagrantfile;

    /**
     * Generate an EditorConfig file?
     *
     * @var boolean
     */
    private $editorConfig;

    /**
     * Project php version.
     *
     * @var string
     */
    private $phpVersion;

    /**
     * Generate .env environment files?
     *
     * @var boolean
     */
    private $environmentFiles;

    /**
     * Generate LGTM configuration files?
     *
     * @var boolean
     */
    private $lgtmConfiguration;

    /**
     * Generate GitHub templates?
     *
     * @var boolean
     */
    private $githubTemplates;

    /**
     * Initialize.
     *
     * @param string  $projectName        The entered project name.
     * @param string  $testingFramework   The entered testing framework.
     * @param string  $license            The entered project license.
     * @param string  $namespace          The entered namespace.
     * @param boolean $gitInit            Initialize a git repo?
     * @param boolean $phpcsConfiguration Generate a PHP Coding Standards Fixer configuration?
     * @param string  $composerKeywords   The entered list of Composer keywords.
     * @param boolean $vagrantfile        Generate a Vagrantfile?
     * @param boolean $editorConfig       Generate an EditorConfig file?
     * @param boolean $phpVersion         The entered php version
     * @param boolean $environmentFiles   Generate .env environment files?
     * @param boolean $lgtmConfiguration  Generate LGTM configuration files?
     * @param boolean $githubTemplates    Generate GitHub templates?
     *
     * @return void
     */
    public function __construct(
        $projectName,
        $testingFramework,
        $license,
        $namespace,
        $gitInit,
        $phpcsConfiguration,
        $composerKeywords,
        $vagrantfile,
        $editorConfig,
        $phpVersion,
        $environmentFiles,
        $lgtmConfiguration,
        $githubTemplates = false
    ) {
        $this->projectName = $projectName;
        $this->testingFramework = $testingFramework;
        $this->license = $license;
        $this->namespace = $namespace;
        $this->gitInit = $gitInit;
        $this->phpcsConfiguration = $phpcsConfiguration;
        $this->composerKeywords = $composerKeywords;
        $this->vagrantfile = $vagrantfile;
        $this->editorConfig = $editorConfig;
        $this->phpVersion = $phpVersion;
        $this->environmentFiles = $environmentFiles;
        $this->lgtmConfiguration = $lgtmConfiguration;
        $this->githubTemplates = $githubTemplates;
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

    /**
     * Get the entered Composer keywords.
     *
     * @return string
     */
    public function getComposerKeywords()
    {
        return $this->composerKeywords;
    }

    /**
     * Whether or not to create a Vagrantfile.
     *
     * @return boolean
     */
    public function withVagrantfile()
    {
        return $this->vagrantfile;
    }

    /**
     * Whether or not to create an EditorConfig file.
     *
     * @return boolean
     */
    public function withEditorConfig()
    {
        return $this->editorConfig;
    }

    /**
     * Get the entered project php version.
     *
     * @return string
     */
    public function getPhpVersion()
    {
        return $this->phpVersion;
    }

    /**
     * Whether or not to create .env environment files.
     *
     * @return boolean
     */
    public function withEnvironmentFiles()
    {
        return $this->environmentFiles;
    }

    /**
     * Whether or not to create LGTM configuration files.
     *
     * @return boolean
     */
    public function withLgtmConfiguration()
    {
        return $this->lgtmConfiguration;
    }

    /**
     * Whether or not to create GitHub template files.
     *
     * @return boolean
     */
    public function withGithubTemplates()
    {
        return $this->githubTemplates;
    }
}
