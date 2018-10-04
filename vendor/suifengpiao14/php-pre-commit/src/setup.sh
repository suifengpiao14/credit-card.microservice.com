#!/bin/sh

if [ -e .git/hooks/pre-commit ];
then
    PRE_COMMIT_EXISTS=1
else
    PRE_COMMIT_EXISTS=0
fi

cp ./vendor/suifengpiao14/php-pre-commit/src/pre-commit .git/hooks/pre-commit
cp ./vendor/suifengpiao14/php-pre-commit/src/phpmd.xml ./phpmd.xml
chmod +x .git/hooks/pre-commit

if [ "$PRE_COMMIT_EXISTS" = 0 ];
then
    echo "Pre-commit git hook is installed!"
else
    echo "Pre-commit git hook is updated!"
fi