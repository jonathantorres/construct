# Changelog

#### v1.4.0 `2015-08-08`
- `Added`
    - User can optionally generate an EditorConfig configuration. Done by [@raphaelstolt](https://github.com/raphaelstolt).
    - Display warning against using `php` as part of project name in micro-packages. Done by [@raphaelstolt](https://github.com/raphaelstolt).
- `Fixes`
    - Update `phpunit` to `4.7.*` and `codeception` to `2.1.*`

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
