Construct
================
[![Build Status](https://travis-ci.org/jonathantorres/construct.svg?branch=master)](https://travis-ci.org/jonathantorres/construct)

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

#### Run tests
Just run `vendor/bin/phpunit` from your project root directory.
