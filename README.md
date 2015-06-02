Construct
================
[![Build Status](https://travis-ci.org/jonathantorres/construct.svg?branch=master)](https://travis-ci.org/jonathantorres/construct)

Cli command to generate a basic PHP project structure.

#### Installation
Construct should be installed globally through composer.

```bash
composer global require jonathantorres/construct
```

Make sure that `~/.composer/vendor/bin` is on your `$PATH`. This way the `construct` executable can be located.

#### Usage
Just run `construct generate` with your `vendor/package` declaration and it will construct a basic PHP project into the `package` directory. For example, if you run `construct generate jonathantorres/logger` it will generate a basic project structure inside the `logger` folder.

```bash
construct generate jonathantorres/logger
```

The current project structure will be the following:
```
├── logger/
│   ├── src/
│   │   ├── Logger.php
│   ├── tests/
│   │   ├── LoggerTest.php
│   ├── vendor/
│   │   ├── ...
│   ├── .gitattributes
│   ├── .gitignore
│   ├── .travis.yml
│   ├── composer.lock
│   ├── composer.json
│   ├── phpunit.xml.dist
│   ├── README.md
│   ├── LICENSE.md
│   ├── CHANGELOG.md
│   ├── CONTRIBUTING.md
```

This is a good starting point. You can continue your work from there.

#### Select testing framework
The `--test` option will allow you to select a testing framework. One of the following is available at the moment: `phpunit`, `phpspec`, `codeception` or `behat`. `phpunit` is currently the default.

```bash
construct generate jonathantorres/logger --test=codeception
```

You can also use the short option `-t`.

```bash
construct generate jonathantorres/logger -t codeception
```

#### Select license
The `--license` option will allow you to select a license for the project to construct. One of the following is
available at the moment: `MIT`, `Apache-2.0`, `GPL-2.0` or `GPL-3.0`. `MIT` is currently the default.

```bash
construct generate jonathantorres/logger --license=Apache-2.0
```

You can also use the short option `-l`.

```bash
construct generate jonathantorres/logger -l Apache-2.0
```

#### Specify a namespace
The `--namespace` option will allow you to specify a namespace for the project to construct. This is totally optional. By default construct will use the package name as the namespace.

```bash
construct generate jonathantorres/logger --namespace=JonathanTorres\\Projects\\Logger
```

You can also use the short option `-s`.

```bash
construct generate jonathantorres/logger -s JonathanTorres\\Projects\\Logger
```

#### Initialize an empty Git repo?
The `--git` option will allow you to initialize an empty Git repository inside the constructed project.

```bash
construct generate jonathantorres/logger --git
```

You can also use the short option `-g`.

```bash
construct generate jonathantorres/logger -g
```

#### Generate a PHP Coding Standards Fixer configuration?
The `--phpcs` option will generate a [PHP Coding Standards Fixer](http://cs.sensiolabs.org/) configuration
within the constructed project. The generated `.php_cs` configuration defaults to the `psr-2` coding style guide.

```bash
construct generate jonathantorres/logger --phpcs
```

You can also use the short option `-p`.

```bash
construct generate jonathantorres/logger -p
```

#### Generate a Vagrantfile?
The `--vagrant` option will generate a basic [Vagrantfile](https://docs.vagrantup.com/v2/vagrantfile/index.html) within the constructed project, defaulting
to the output of a `vagrant init` call. There's no short option available.

```bash
construct generate jonathantorres/logger --vagrant
```

#### Run tests
Just run `composer test` from the project's root directory.
