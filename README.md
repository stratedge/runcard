# Runcard
A command-line-utility for creating a Slim3 route file from a configuration file

## Installation

Via [Composer](http://www.composer.com)

```sh
$ composer require stratedge/runcard
```
As Runcard is best used to generate a routes file during development, it may be better to require it as a development-only library:

```sh
$ composer require --dev stratedge/runcard
```

Requires PHP 5.3.0 or newer.

## Todo

* Invokable
	* Clean up the giant if/else statement in the constructor
* Configuration
	* Output
		* Throw an error if given path is not writable
* Unit Tests
	* Write initial unit tests....
* Documentation
	* Document everything...

## Contributors

* Jarret Byrne [http://jarretbyrne.com](http://jarretbyrne.com)