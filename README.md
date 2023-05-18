# Wordless Framework

The [Wordless](https://github.com/thbighead/Wordless) project framework.

## Directory and files organization

```
| src
| \
|  | Abstractions (Keep all classes abstractions)
|  | \
|  |  | Cachers (Classes to keep internal caches)
|  |  | Guessers (Classes to guess values)
|  |  | StubMounters (Classes to mount stub contents into new files)
|  | Adapters (Keep all classes adapted from vendor classes)
|  | Bootables (Keep boot classes)
|  | Commands (Wordless CLI commands. Make new as you wish, customize carefully)
|  | Contracts (Useful classes to contract Wordless Classes)
|  | config (Keep base configuration files)
|  | Exception (Keep custom Exceptions. Better then if-else)
|  | Helpers (Keep helper classes)
|  | stubs (Keep files stubs to generate new ones)
|>.env.example (Used to create new .env files)
|>composer.json (Composer)
|>console (Wordless CLI file)
|>wp-cli.yml (WP-CLI config file)
```

## Test environment

To begin a completely new test environment, so you can test a full Wordless installation, use the following command:

```shell
php console test:environment
```

This should create a folder named `test-environment` with a fresh Wordless installation based on your local `wordless`
and `wordless-framework` projects. **Note that this command is only available at this project for test purposes,
Wordless fresh installations (even the one created by this command) can't use it.**

> **IMPORTANT:** If you want to run a composer command inside `test-environment` directory, use `vendor/bin/composer`
> instead of only `composer`. Using only the `composer` command may reference to `/var/www/vendor/bin/composer` making
> your Composer script changes to not be applied.

If you already have installed a test environment you may restart it by using `--force` (or `-f`) flag:

```shell
php console test:environment -f
```

> When using `--force` (or `-f`) flag you may also use `--drop-db` flag to also restarts the test database:
> ```shell
> php console test:environment -f --drop-db
> ```

## Exception codes

0. *Caught internally*: those exceptions should never interrupt the application because they are (or should be) always
caught by the framework itself everytime it would be thrown;
1. *Development error*: it means that the exception is thrown to force developers to always use a try-catch block
when calling what throws it. It's a kind of exception that should only interrupt the application when a develop
mistake occurs;
2. *Logic control*: it controls any application logic. It may be wrapped by a try-catch code or not depending on what
developers expect;
3. *Intentional interrupt*: should never be caught. Exceptions are made to ALWAYS interrupt the application. 
