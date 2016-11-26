# How to contribute

Thanks for contributing to construct! Just follow these single guidelines:
- You must follow the PSR-2 coding standard. Please see [PSR-2](http://www.php-fig.org/psr/psr-2/) for more details. With these tweaks:
    - Always use spaces!
    - Ensure the coding standard compliance before committing or opening pull requests by running `composer cs-fix` or `composer cs-lint` in the root directory of this repository.

- All features or bugfixes must have an associated issue for discussion. If you want to work on an issue that is already created, please leave a comment on it indicating that you are working on it.

- You must use [feature / topic branches](https://git-scm.com/book/en/v2/Git-Branching-Branching-Workflows) to ease the merge of contributions.

- Commit messages should read like a sentence, including the period, _ideally_ describing the why not the what.
    - Commits fixing bugs should reference a present issue via `Fixes #<bug-issue-number>.`.
    - Commits adding a feature or enhancement should reference a present issue via `Closes #<feature|enhancement-issue-number>.`.
