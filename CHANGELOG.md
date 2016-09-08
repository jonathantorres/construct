# Changelog

#### v1.11.0 `2016-**-**`
- `Added`
    - User can optionally generate [GitHub documentation](https://github.com/blog/2233-publish-your-project-documentation-with-github-pages) files. Done by [@raphaelstolt](https://github.com/raphaelstolt).

    - A Travis CI badge is now added in the constructed README.md.
- `Fixed`
    - Generated `phpspec` configuration file has a `.dist` extension and a `specs` directory is created.

#### v1.10.1 `2016-07-09`
- `Fixed`
    - License, testing framework, and PHP version are validated from configuration file. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#126](https://github.com/jonathantorres/construct/issues/126).
    - Fixes on misleading documentation on configuration file. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#128](https://github.com/jonathantorres/construct/issues/128).
    - Add phpcs contribution guidelines.
    - Update `php-cs-fixer` vendor name. Done by [@raphaelstolt](https://github.com/raphaelstolt).

#### v1.10.0 `2016-05-28`
- `Added`
    - User can load common option settings from a configuration file. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#86](https://github.com/jonathantorres/construct/issues/86).
- `Fixed`
    - Generated `.env` file is now added on `.gitignore`. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#117](https://github.com/jonathantorres/construct/issues/117).
    - Xdebug is now disabled on constructed `.travis.yml` file. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#119](https://github.com/jonathantorres/construct/issues/119).
    - Wording improvements on constructed `README`. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#112](https://github.com/jonathantorres/construct/issues/112).

#### v1.9.0 `2016-04-10`
- `Added`
    - User can optionally generate a Code of Conduct file which is adapted from the [Contributor Covenant](http://contributor-covenant.org), version 1.4. Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - Composer test scripts for Codeception, behat and phpspec. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#102](https://github.com/jonathantorres/construct/issues/102).
- `Fixed`
    - User can use the more intent revealing option alias `--test-framework` to select a testing framework. The `--test` option will be removed in a future release. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#96](https://github.com/jonathantorres/construct/issues/96).
    - Broken link in README when using the --github-templates option. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#105](https://github.com/jonathantorres/construct/issues/105).
    - Default PHP version is no longer set to the exact patch version. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#85](https://github.com/jonathantorres/construct/issues/85).
    - Missing PHP version in constructed Travis file. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#91](https://github.com/jonathantorres/construct/issues/91).

#### v1.8.0 `2016-03-12`
- `Added`
    - User can optionally generate [GitHub templates](https://github.com/blog/2111-issue-and-pull-request-templates). Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - Test generated project on php nightly. Done by [@raphaelstolt](https://github.com/raphaelstolt).

#### v1.7.1 `2016-01-24`
- `Fixed`
    - Use installed php version by default. See [#77](https://github.com/jonathantorres/construct/issues/77).
    - Php cs fixer composer script is now generated. Fix by [@raphaelstolt](https://github.com/raphaelstolt).

#### v1.7.0 `2015-12-30`
- `Added`
    - Interactive console mode. See [#14](https://github.com/jonathantorres/construct/issues/14).

#### v1.6.0 `2015-12-17`
- `Added`
    - User can optionally generate .env environment files. Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - User can optionally generate LGTM configuration files. Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - Allow user to specify php7 as the project's required php version.
- `Fixed`
    - Run travis on php versions greater or equal than the generated project. See [#72](https://github.com/jonathantorres/construct/issues/72).

#### v1.5.0 `2015-11-07`
- `Added`
    - Add `--php` option to specify a php version for your project.
    - Generated project now uses phpunit `4.8`.
    - Show more console output when initializing a git repo, bootstrapping codeception and initializing behat.

#### v1.4.3 `2015-09-17`
- `Fixed`
    - PHPUnit test is only generated if using `phpunit` as your testing framework.
    - Generate `phpspec.yml` file. When using `phpspec` as your testing framework.
    - Initialize `behat` if using it as your testing framework.
    - Bootstrap `codeception` if using it as your testing framework.

#### v1.4.2 `2015-09-07`
- `Fixed`
    - Include author name on GPL license files.
    - No longer using illuminate components.
    - Improved tests. Added integration test.

#### v1.4.1 `2015-08-29`
- `Fixed`
    - Fix console dependency. Always use latest stable version.
    - Minor docs updates.

#### v1.4.0 `2015-08-08`
- `Added`
    - User can optionally generate an EditorConfig configuration. Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - Display warning against using `php` as part of project name in micro-packages. Done by [@raphaelstolt](https://github.com/raphaelstolt).
- `Fixed`
    - Update `phpunit` to `4.7.*` and `codeception` to `2.1.*`.

#### v1.3.0 `2015-06-09`
- `Added`
    - User can optionally generate a Vagrant file. Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - User can optionally specify composer keywords. Done by [@raphaelstolt](https://github.com/raphaelstolt).

#### v1.2.0 `2015-05-27`
- `Added`
    - User can optionally generate a PHP Coding Standards Fixer configuration. Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - `CHANGELOG.md` and `CONTRIBUTING.md` are now also generated. Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - `README.md` has more additional content. Done by [@raphaelstolt](https://github.com/raphaelstolt).
- `Fixed`
    - Generated `LICENSE.md` now contains author name. Fix by [@raphaelstolt](https://github.com/raphaelstolt). See [#42](https://github.com/jonathantorres/construct/issues/42).
    - Code coverage included on generated `phpunit.xml.dist` file. Fix by [@raphaelstolt](https://github.com/raphaelstolt). See [#7](https://github.com/jonathantorres/construct/issues/7).

#### v1.1.0 `2015-05-18`
- `Added`
    - User can optionally initialize an empty git repository.
    - User can now specify a namespace for the constructed project. See [#12](https://github.com/jonathantorres/construct/issues/12).

#### v1.0.3 `2015-05-14`
- `Added`
    - User can now select a license. Default is `MIT`. Done by [@hannesvdvreken](https://github.com/hannesvdvreken). See [#13](https://github.com/jonathantorres/construct/issues/13).
    - `.gitattributes` file is now generated. Done by [@hannesvdvreken](https://github.com/hannesvdvreken).
    - Run `composer install` on project creation. Done by [@raphaelstolt](https://github.com/raphaelstolt). See [#3](https://github.com/jonathantorres/construct/issues/3).
- `Fixed`
    - Generated PHPUnit file is now `phpunit.xml.dist`. Fix by [@mikeSimonson](https://github.com/mikeSimonson). See [#6](https://github.com/jonathantorres/construct/issues/6).
    - Improvements on generated `.travis.yml` file. Fix by [@hannesvdvreken](https://github.com/hannesvdvreken).
    - Filemode on `construct` executable file. Fix by [@agostlg](https://github.com/agostlg).
    - Author details on `composer.json` are determined from user's git config. Fix by [@raphaelstolt](https://github.com/raphaelstolt). See [#9](https://github.com/jonathantorres/construct/issues/9).
    - Package name is validated correctly. Using the same as composer. See [#10](https://github.com/jonathantorres/construct/issues/10).

#### v1.0.2 `2015-05-11`
- `Added`
    - Specify a testing framework.
    - Add author info on `composer.json`.

#### v1.0.1 `2015-04-25`
- `Fixed` bug on autoload.

#### v1.0.0 `2015-04-25`
- First release.
