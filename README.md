
# Construct
[![Build Status](https://travis-ci.org/jonathantorres/construct.svg?branch=master)](https://travis-ci.org/jonathantorres/construct)
[![Build Status](https://ci.appveyor.com/api/projects/status/github/jonathantorres/construct?svg=true)](https://ci.appveyor.com/project/jonathantorres/construct)
[![Version](http://img.shields.io/packagist/v/jonathantorres/construct.svg?style=flat)](https://packagist.org/packages/jonathantorres/construct)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat)](https://github.com/php-pds/skeleton)

A PHP project/micro-package generator for [PDS](http://php-pds.com/) compliant projects or micro-packages.

## Installation
Construct should be installed globally through composer.

```bash
composer global require jonathantorres/construct
```

Make sure that `~/.composer/vendor/bin` is on your `$PATH`. This way the `construct` executable can be located.

## Assumptions

As Construct utilizes Composer's CLI, it's assumed that Composer is installed. When using the option to initialize an empty Git repo (i.e. `--git` or `-g`) it's also assumed that Git is installed.

## Usage
Just run `construct generate` with your `vendor/package` declaration and it will construct a basic PHP project into the `package` directory. For example, if you run `construct generate jonathantorres/logger` it will generate a basic project structure inside the `logger` folder.

```bash
construct generate jonathantorres/logger
```

The generated project structure will look like the following `tree` excerpt. Files and directories in parentheses are optional.
```
├── logger
│   ├── CHANGELOG.md
│   ├── (CONDUCT.md)
│   ├── composer.json
│   ├── composer.lock
│   ├── CONTRIBUTING.md
│   ├── (.appveyor.yml)
│   ├── (.editorconfig)
│   ├── (.env)
│   ├── (.env.example)
│   ├── (.git)
│   │   └── ...
│   ├── .gitattributes
│   ├── (.github)
│   │   ├── CONTRIBUTING.md
│   │   ├── ISSUE_TEMPLATE.md
│   │   └── PULL_REQUEST_TEMPLATE.md
│   ├── .gitignore
│   ├── .gitmessage
│   ├── (.lgtm)
│   ├── (bin)
│   │   └── cli-script
│   ├── LICENSE.md
│   ├── (MAINTAINERS)
│   ├── (.php_cs)
│   ├── (phpunit.xml.dist)
│   ├── README.md
│   ├── (docs)
│   │   └── index.md
│   ├── src
│   │   └── Logger.php
│   ├── tests
│   │   └── LoggerTest.php
│   ├── .travis.yml
│   ├── (Vagrantfile)
│   └── vendor
│           └── ...
```

This is a good starting point. You can continue your work from there.

## Select testing framework
The `--test-framework` or `--test` option will allow you to select a testing framework. One of the following is available at the moment: `phpunit`, `phpspec`, `codeception` or `behat`. `phpunit` is currently the default.

```bash
construct generate jonathantorres/logger --test-framework=codeception
```

You can also use the short option `-t`.

```bash
construct generate jonathantorres/logger -t codeception
```

## Select license
The `--license` option will allow you to select a license for the project to construct. One of the following is
available at the moment: `MIT`, `Apache-2.0`, `GPL-2.0` or `GPL-3.0`. `MIT` is currently the default.

```bash
construct generate jonathantorres/logger --license=Apache-2.0
```

You can also use the short option `-l`.

```bash
construct generate jonathantorres/logger -l Apache-2.0
```

## Specify a namespace
The `--namespace` option will allow you to specify a namespace for the project to construct. This is totally optional. By default construct will use the package name as the namespace.

```bash
construct generate jonathantorres/logger --namespace=JonathanTorres\\Projects\\Logger
```

You can also use the short option `-s`.
```bash
construct generate jonathantorres/logger -s JonathanTorres\\Projects\\Logger
```

## Specify a CLI framework
The optional `--cli-framework` option will allow you to specify a CLI framework for the project to construct, while also creating a bin directory with an initial CLI script in it, adding a bin key in the project's composer.json, and an initial [AppVeyor](https://www.appveyor.com) configuration. When the option has been set without a CLI composer package the `symfony/console` package will be used per default. There's no short option available.

```bash
construct generate jonathantorres/logger --cli-framework=zendframework/zend-console
```

## Specify PHP version
The `--php` option will allow you to specify the minimum required PHP version that your project will support. Construct will use the currently installed version if not specified.

```bash
construct generate jonathantorres/logger --php=5.6.31
```

## Specify Composer keywords
The optional `--keywords` option will allow you to specify a comma separated list of [Composer keywords](https://getcomposer.org/doc/04-schema.md#keywords).

```bash
construct generate jonathantorres/logger --keywords=log,logging
```

You can also use the short option `-k`.

```bash
construct generate jonathantorres/logger -k=log,logging
```

## Initialize an empty Git repo?
The `--git` option will allow you to initialize an empty Git repository inside the constructed project.

```bash
construct generate jonathantorres/logger --git
```

You can also use the short option `-g`.

```bash
construct generate jonathantorres/logger -g
```

## Generate a PHP Coding Standards Fixer configuration?
The `--phpcs` option will generate a [PHP Coding Standards Fixer](http://cs.sensiolabs.org/) configuration within the constructed project and add a Travis CI script for validation during builds. The generated `.php_cs` configuration defaults to the `PSR-2` coding style guide.

```bash
construct generate jonathantorres/logger --phpcs
```

You can also use the short option `-p`.

```bash
construct generate jonathantorres/logger -p
```

## Generate a Vagrantfile?
The `--vagrant` option will generate a basic [Vagrantfile](https://docs.vagrantup.com/v2/vagrantfile/index.html) within the constructed project, defaulting to the output of a `vagrant init` call plus a minimal [vagrant-cachier](http://fgrehm.viewdocs.io/vagrant-cachier) plugin configuration. There's no short option available.

```bash
construct generate jonathantorres/logger --vagrant
```

## Generate an EditorConfig configuration?
The `--editor-config` option will generate an [EditorConfig](http://editorconfig.org) configuration
within the constructed project.

```bash
construct generate jonathantorres/logger --editor-config
```

You can also use the short option `-e`.

```bash
construct generate jonathantorres/logger -e
```

## Generate .env enviroment files?
The `--env` option will generate [.env](https://github.com/vlucas/phpdotenv) environment files within the constructed project for keeping `sensitive` information out of it. There's no short option available.

```bash
construct generate jonathantorres/logger --env
```

## Generate LGTM configuration files?
The `--lgtm` option will generate [LGTM](https://lgtm.co) configuration files within the constructed project. There's no short option available.

```bash
construct generate jonathantorres/logger --lgtm
```

## Generate common GitHub files?
The `--github` option will generate common GitHub (i.e. `templates` and `docs`) files. There's no short option available.

```bash
construct generate jonathantorres/logger --github
```

## Generate GitHub template files?
The `--github-templates` option will generate [GitHub template](https://github.com/blog/2111-issue-and-pull-request-templates) files within the constructed project into a folder conventionally named `.github`. It also will move `CONTRIBUTING.md` into it. There's no short option available, but the option is implicated with the `github` option.

```bash
construct generate jonathantorres/logger --github-templates
```

## Generate GitHub documentation files?
The `--github-docs` option will generate [GitHub documentation](https://github.com/blog/2233-publish-your-project-documentation-with-github-pages) files within the constructed project into a folder conventionally named `docs`. There's no short option available, but the option is implicated with the `github` option.

```bash
construct generate jonathantorres/logger --github-docs
```

## Generate a Code of Conduct?
The `--code-of-conduct` option will generate a Code of Conduct file named `CONDUCT.md` within the constructed project and also add a reference to it in the generated `README.md`. The used Code of Conduct is adapted from the [Contributor Covenant](http://contributor-covenant.org), version 1.4. There's no short option available.

```bash
construct generate jonathantorres/logger --code-of-conduct
```

## Use a configuration for recurring settings
The `--config` option allows the usage of a configuration file in the `YAML` format. There are two ways to provide such a configuration file: One is to provide a specific file as an option argument, the other is to put a `.construct` configuration file in the home directory of your system. For the structure of a configuration file have a look at the [.construct](example/.construct) example file. When no configuration keys are provided for settings having a default value (i.e. `test-framework`, `license`, `php`) their default value is used.

```bash
construct generate jonathantorres/logger --config /path/to/config.yml
```

You can also use the short option `-c`.

```bash
construct generate jonathantorres/logger -c /path/to/config.yml
```

When there's a `.construct` configuration file in your home directory it will be used per default. If required it's possible to disable the usage via the `--ignore-default-config` option or the equivalent short option `-i`.

## Interactive Mode
This mode will ask you all the required (and optional) information to generate your project.

```bash
construct generate:interactive
```

## Running tests
``` bash
composer construct:test
```

## License
This library is licensed under the MIT license. Please see [LICENSE](LICENSE.md) for more details.

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more details.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for more details.
