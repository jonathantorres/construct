Construct
================
[![Build Status](https://travis-ci.org/jonathantorres/construct.svg?branch=master)](https://travis-ci.org/jonathantorres/construct)
[![Done Issues](https://badge.waffle.io/jonathantorres/construct.png?label=done&title=Done)](https://waffle.io/jonathantorres/construct)

Cli command to generate a basic PHP project structure.

#### Installation
Construct should be installed globally through composer.

```bash
composer global require "jonathantorres/construct=~1.0"
```

Make sure that `~/.composer/vendor/bin` is on your `$PATH`. This way the `construct` executable can be located.

#### Usage
Just run `construct generate` with your `vendor/package` declaration and it will create a basic php project on the `package` directory. For example, if you run `construct generate jonathantorres/logger` it will generate a basic project structure inside the `logger` folder.

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

You can also use the short code `-t`

```bash
construct generate jonathantorres/logger -t codeception
```

#### Run tests
Just run `vendor/bin/phpunit` from your project root directory.
