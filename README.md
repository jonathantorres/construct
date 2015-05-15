Construct
================
[![Build Status](https://travis-ci.org/jonathantorres/construct.svg?branch=master)](https://travis-ci.org/jonathantorres/construct)
[![Done Issues](https://badge.waffle.io/jonathantorres/construct.png?label=done&title=Done)](https://waffle.io/jonathantorres/construct)

Cli command to generate a basic PHP project structure.

#### Installation
Construct should be installed globally through composer.

```bash
composer global require jonathantorres/construct
```

Make sure that `~/.composer/vendor/bin` is on your `$PATH`. This way the `construct` executable can be located.

#### Usage
Just run `construct generate` with your `vendor/package` declaration and it will construct a basic php project on the `package` directory. For example, if you run `construct generate jonathantorres/logger` it will generate a basic project structure inside the `logger` folder.

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
│   ├── .gitignore
│   ├── .travis.yml
│   ├── composer.json
│   ├── phpunit.xml
│   ├── README.md
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

#### Run tests
Just run `vendor/bin/phpunit` from your project root directory.
