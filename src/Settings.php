<?php

declare(strict_types = 1);

namespace Construct;

class Settings
{
    /**
     * The entered project name.
     *
     * @var string
     */
    private $projectName;

    /**
     * Camel case version of vendor name.
     * ex: JonathanTorres
     *
     * @var string
     */
    protected $vendorUpper;

    /**
     * Lower case version of vendor name.
     * ex: jonathantorres
     *
     * @var string
     */
    protected $vendorLower;

    /**
     * Camel case version of project name.
     * ex: Construct
     *
     * @var string
     */
    protected $projectUpper;

    /**
     * Lower case version of project name.
     * ex: construct
     *
     * @var string
     */
    protected $projectLower;

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
     * Generate GitHub documentation files?
     *
     * @var boolean
     */
    private $githubDocs;

    /**
     * Generate Code of Conduct file?
     *
     * @var boolean
     */
    private $codeOfConduct;

    /**
     * The entered CLI framework.
     *
     * @var string
     */
    private $cliFramework;

    /**
     * Get the entered project name.
     *
     * @return string
     */
    public function getProjectName(): string
    {
        return $this->projectName;
    }

    /**
     * Set the entered project name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setProjectName(string $name)
    {
        $this->projectName = $name;
    }

    /**
     * Set the studly case version of the vendor name.
     *
     * @param string $vendorUpper
     *
     * @return void
     */
    public function setVendorUpper(string $vendorUpper)
    {
        $this->vendorUpper = $vendorUpper;
    }

    /**
     * Get the studly case version of the vendor name.
     *
     * @return string
     */
    public function getVendorUpper(): string
    {
        return $this->vendorUpper;
    }

    /**
     * Set the lower case version of the vendor name.
     *
     * @param string $vendorLower
     *
     * @return void
     */
    public function setVendorLower(string $vendorLower)
    {
        $this->vendorLower = $vendorLower;
    }

    /**
     * Get the lower case version of the vendor name.
     *
     * @return string
     */
    public function getVendorLower(): string
    {
        return $this->vendorLower;
    }

    /**
     * Set the studly case version of the project name
     *
     * @param string $projectUpper
     *
     * @return void
     */
    public function setProjectUpper(string $projectUpper)
    {
        $this->projectUpper = $projectUpper;
    }

    /**
     * Get the studly case version of the project name.
     *
     * @return string
     */
    public function getProjectUpper(): string
    {
        return $this->projectUpper;
    }

    /**
     * Set the lower case version of the project name.
     *
     * @param string $projectLower
     *
     * @return void
     */
    public function setProjectLower(string $projectLower)
    {
        $this->projectLower = $projectLower;
    }

    /**
     * Get the lower case version of the project name
     *
     * @return string
     */
    public function getProjectLower(): string
    {
        return $this->projectLower;
    }

    /**
     * Get the entered testing framework.
     *
     * @return string
     */
    public function getTestingFramework(): string
    {
        return $this->testingFramework;
    }

    /**
     * Set the entered testing framework.
     *
     * @param string $testingFramework
     *
     * @return void
     */
    public function setTestingFramework(string $testingFramework)
    {
        $this->testingFramework = $testingFramework;
    }

    /**
     * Get the entered license.
     *
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * Set the entered license.
     *
     * @param string $license
     *
     * @return void
     */
    public function setLicense(string $license)
    {
        $this->license = $license;
    }

    /**
     * Get the entered namespace.
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Set the entered namespace.
     *
     * @param string $namespace
     *
     * @return void
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
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
     * Set whether or not to initialize a git repo on the project.
     *
     * @param boolean $gitInit
     *
     * @return void
     */
    public function setGitInit($gitInit)
    {
        $this->gitInit = $gitInit;
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
     * Set whether or not to use phpcs on the project.
     *
     * @param boolean $configuration
     *
     * @return void
     */
    public function setPhpcsConfiguration($configuration)
    {
        $this->phpcsConfiguration = $configuration;
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
     * Set the entered Composer keywords.
     *
     * @param string $keywords
     *
     * @return void
     */
    public function setComposerKeywords($keywords)
    {
        $this->composerKeywords = $keywords;
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
     * Set whether or not to create a Vagrantfile.
     *
     * @param boolean $vagrantfile
     *
     * @return void
     */
    public function setVagrantfile($vagrantfile)
    {
        $this->vagrantfile = $vagrantfile;
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
     * Set whether or not to create an EditorConfig file.
     *
     * @param boolean $config
     *
     * @return void
     */
    public function setEditorConfig($config)
    {
        $this->editorConfig = $config;
    }

    /**
     * Get the entered project php version.
     *
     * @return string
     */
    public function getPhpVersion(): string
    {
        return $this->phpVersion;
    }

    /**
     * Set the entered project php version.
     *
     * @param string $version
     *
     * @return void
     */
    public function setPhpVersion(string $version)
    {
        $this->phpVersion = $version;
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
     * Set whether or not to create .env environment files.
     *
     * @param boolean $envFiles
     *
     * @return void
     */
    public function setEnvironmentFiles($envFiles)
    {
        $this->environmentFiles = $envFiles;
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
     * Set whether or not to create an LGTM configuration file.
     *
     * @param boolean $configuration
     *
     * @return void
     */
    public function setLgtmConfiguration($configuration)
    {
        $this->lgtmConfiguration = $configuration;
    }

    /**
     * Whether or not to create GitHub template files.
     *
     * @return boolean
     */
    public function withGithubTemplates(): bool
    {
        return $this->githubTemplates;
    }

    /**
     * Set whether or not to create GitHub template files.
     *
     * @param boolean $templates
     *
     * @return void
     */
    public function setGithubTemplates($templates)
    {
        $this->githubTemplates = $templates;
    }

    /**
     * Whether or not to create GitHub documentation files.
     *
     * @return boolean
     */
    public function withGithubDocs(): bool
    {
        return $this->githubDocs;
    }

    /**
     * Set whether or not to create GitHub documentation files.
     *
     * @param boolean $docs
     *
     * @return void
     */
    public function setGithubDocs($docs)
    {
        $this->githubDocs = $docs;
    }

    /**
     * Whether or not to create a Code of Conduct file.
     *
     * @return boolean
     */
    public function withCodeOfConduct(): bool
    {
        return $this->codeOfConduct;
    }

    /**
     * Set whether or not to create a Code of Conduct file.
     *
     * @param boolean $codeOfConduct
     *
     * @return void
     */
    public function setCodeOfConduct($codeOfConduct)
    {
        $this->codeOfConduct = $codeOfConduct;
    }

    /**
     * Whether or not to add a CLI framework.
     *
     * @return boolean
     */
    public function withCliFramework(): bool
    {
        return $this->cliFramework !== null;
    }

    /**
     * Get the entered CLI framework.
     *
     * @return string
     */
    public function getCliFramework()
    {
        return $this->cliFramework;
    }

    /**
     * Set the CLI framework.
     *
     * @param string $cliFramework
     *
     * @return void
     */
    public function setCliFramework($cliFramework)
    {
        $this->cliFramework = $cliFramework;
    }
}
