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