<?php

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
    public function getProjectName()
    {
        return $this->projectName;
    }

    public function setProjectName($name)
    {
        $this->projectName = $name;
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

    public function setTestingFramework($testingFramework)
    {
        $this->testingFramework = $testingFramework;
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

    public function setLicense($license)
    {
        $this->license = $license;
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

    public function setNamespace($namespace)
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

    public function setVagrantfile($vagrantFile)
    {
        $this->vagrantFile = $vagrantfile;
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

    public function setEditorConfig($config)
    {
        $this->editorConfig = $config;
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

    public function setPhpVersion($version)
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

    public function setLgtmConfiguration($configuration)
    {
        $this->lgtmConfiguration = $configuration;
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

    public function setGithubTemplates($templates)
    {
        $this->githubTemplates = $templates;
    }

    /**
     * Whether or not to create GitHub documentation files.
     *
     * @return boolean
     */
    public function withGithubDocs()
    {
        return $this->githubDocs;
    }

    public function setGithubDocs($docs)
    {
        $this->githubDocs = $docs;
    }

    /**
     * Whether or not to create a Code of Conduct file.
     *
     * @return boolean
     */
    public function withCodeOfConduct()
    {
        return $this->codeOfConduct;
    }

    public function setCodeOfConduct($codeOfConduct)
    {
        $this->codeOfConduct = $codeOfConduct;
    }

    /**
     * Whether or not to add a CLI framework.
     *
     * @return boolean
     */
    public function withCliFramework()
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
     */
    public function setCliFramework($cliFramework)
    {
        $this->cliFramework = $cliFramework;
    }
}
