# Wordless Framework

The [Wordless](https://github.com/thbighead/Wordless) project framework.

## Directory and files organization

### Main directories and files

```
| assets (Publishable directories)
| \
|  | config (Keep base configuration files)
|  | stubs (Keep files stubs to generate new ones)
| src
| \
|  | Application (Classes implementation, just like in project app directory)
|  | \
|  |  | Cachers (Classes to keep internal caches)
|  |  | Commands (Wordless CLI commands. Make new as you wish, customize carefully)
|  |  | Guessers (Classes to guess values)
|  |  | Helpers (Keep helper classes)
|  |  | Libraries (Library directories with useful abstractions)
|  |  | Listeners (Keep listener classes for Wordpress hooks (Events))
|  |  | Mounters (Class implementations which mount files with dynamic contents from stubs)
|  |  | Providers (Classes which register any Application class as a group)
|  | Core (Core Wordless classes, they raise all framework functionalities)
|  | Infrastructure (Abstract classes used by Application and Wordpress)
|  | Wordpress (Wordpress adapted class abstractions)
| tests (Automated tests)
| \
|  | Feature (Application endpoint tests)
|  | Unit (Class methods unitary tests)
|>.env.example (Used to create new .env files)
|>composer.json (Composer)
|>console (Wordless CLI file)
|>phpunit.xml (PHP Unit configuration)
```

### Relative directories

Inside any [main directory](#main-directories-and-files) we may have the following directories:

```
| Contracts (Useful Interfaces or abstract classes with abstract methods)
| DTO (Useful DTO implementations to avoid complex array data configuration)
| Enums (Useful Enums to avoid magic values)
| Exceptions (Keep custom Exceptions. Better then if-else)
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
