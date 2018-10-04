# PHP [pre-commit](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks)

> GIT pre-commit script appropriate for any PHP project.
> A pre-commit script that validates syntax errors in **PHP**. It also validates PHP files against `PSR2` coding styles.

## About

This is a pre commit script that checks added, copied, modified or renamed files for syntax errors and `PSR2` coding standards.

## Installation
```bash
 composer require --dev suifengpiao14/php-pre-commit:dev-master .
 ```
 Or alternatively, include a dependency for smgladkovskiy/phpcs-git-pre-commit in your composer.json file manually:
```json
{
    "require-dev": {
        "suifengpiao14/php-pre-commit": "dev-master"
    }
}
```

 To enable code check, аdd to post-install-cmd and post-update-cmd in composer.json installation script:
```json
"post-install-cmd": [
    "sh ./vendor/suifengpiao14/php-pre-commit/src/setup.sh"
],
"post-update-cmd": [
    "sh ./vendor/suifengpiao14/php-pre-commit/src/setup.sh"
]
```
Then run composer install or composer update. pre-commit hook will be installed or updated if it already exists.

## Usage

This will work automatically before every commit.

