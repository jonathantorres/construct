# Changelog

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
