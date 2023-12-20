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

0. *Caught internally*: those exceptions should never interrupt application because they are (or should be) always
caught by the framework itself everytime it would be thrown;
1. *Development error*: it's a kind of exception that should only interrupt application when a develop mistake occurs;
2. *Logic control*: it controls any application logic. It may be wrapped by a try-catch code or not depending on what
developers expect;
3. *Intentional interrupt*: should never be caught, those exceptions are made to ALWAYS interrupt application. 

## Docker

To provide a development environment, Wordless offers the following containers:

### Workspace

This is the main container, where you can access PHP, Composer, and the Wordless CLI console. Generally, this is where 
you will interact with your application most of the time.

### MariaDB

This container is responsible for creating and managing your database.

### Adminer

Adminer is a container that allows interaction with the database through the browser, providing an interface to perform 
various tasks necessary for database-related development.

### Commands

#### Starting containers 
To start the containers, use the `up` command, which will initiate each container. If the containers haven't been created, 
this command will also build them.

> The `-d` flag allows you to execute this command in non-verbose mode. In other words, you won't receive the outputs of 
> your containers, leaving the terminal free for other tasks.
> ```shell
> docker compose up -d
> ```

#### Executing Containers
Once the containers are created and started, you can navigate inside them using the `exec` command. Inside each 
container, you can leverage all its specific functionalities characteristic of each container. Specify which container 
to execute; in this case, we are executing the workspace.

> The `--user` flag indicates the user to be used for logging into the container. Usually, `laradock` is the default, 
> but in some cases where a profile with greater responsibility is needed, you can use `root`.
> ```shell
> docker compose exec --user=laradock workspace bash
> ```
