# How to contribute

Thanks for contributing to construct! Just follow these single guidelines:
- You must follow the PSR-2 coding standard. More info [here](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md). With these tweaks:
    - Always use spaces!
    - Ensure the coding standard compliance before committing or opening pull requests by running `php-cs-fixer fix .` or `composer cs-fix` in the root directory of this repository.

- All features or bugfixes must have an associated issue for discussion. If you want to work on a issue that is already created, please leave a comment on it indicating that you are working on it.

- Commit messages should read like a sentence, including the period, _ideally_ describing the why not the what.
    - Commits fixing bugs should reference a present issue via `Fixes #<bug-issue-number>.`.
    - Commits adding a feature or enhancement should reference a present issue via `Closes #<feature|enhancement-issue-number>.`.
