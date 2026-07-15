# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/thephpleague/glide).

## Pull Requests

- **[PER Coding Style 3.0](https://www.php-fig.org/per/coding-style/)** - The easiest way to apply the conventions is to run `./vendor/bin/php-cs-fixer fix --allow-risky=yes`.
- **Add tests!** - Your patch won't be accepted if it doesn't have tests.
- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- **Consider our release cycle** - We try to follow [SemVer v2.0.0](http://semver.org/). Randomly breaking public APIs is not an option.
- **Create feature branches** - Don't ask us to pull from your master branch.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please squash them before submitting.

## Running Tests

``` bash
## Local environment
$ ./vendor/bin/phpunit

## Docker
$ docker compose run --rm tests
```
## Statis Analysis

``` bash
## Local environment
$ ./vendor/bin/phpstan

## Docker
$ docker compose run --rm analysis
```

## Code standards

``` bash
## Local environment
$ ./vendor/bin/php-cs-fixer fix --allow-risky=yes

## Docker
$ docker compose run --rm cs
```

**Happy coding**!
