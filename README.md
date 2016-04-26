# Post JIRA comment with build details step

## How to use this Step

Can be run directly with the [bitrise CLI](https://github.com/bitrise-io/bitrise),
just `git clone` this repository, `cd` into it's folder in your Terminal/Command Line
and call `bitrise run test`.

*Check the `bitrise.yml` file for required inputs which have to be
added to your `.bitrise.secrets.yml` file!*


## Share your own Step

You can share your Step or step version with the [bitrise CLI](https://github.com/bitrise-io/bitrise). Just run `bitrise share` and follow the guide it prints.

## Source

The source code for the PHAR archive is available here : https://github.com/dag-io/post-jira-comment-with-build-details

## Test

```
php -S 127.0.0.1:8888 -t . test.php
```

Then :

```
bitrise run test
```
