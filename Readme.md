# Simovative PRG-HTTP-Framework
[![Build Status](https://travis-ci.org/Simovative/zeus.svg?branch=master)](https://travis-ci.org/Simovative/zeus)
[![Coveralls](https://coveralls.io/repos/Simovative/zeus/badge.svg?branch=master)](https://coveralls.io/github/Simovative/zeus?branch=master)
[![CodeCov](https://codecov.io/gh/Simovative/zeus/branch/master/graph/badge.svg)](https://codecov.io/gh/Simovative/zeus)

## Index
1.[Design principles](#design)<br>
2.[Quickstart](#quickstart)<br>
3.[General structure](#structure)<br>
4.[Modifying components](#modifying)<br>
5.[Build](#build)<br>


## Design principles<a name="design"></a>

This framework is made specifically for applications relying heavily on the
HTTP protocol and the Post-Redirect-Get (PRG) design pattern. From our point 
of view it could be extended to create any kind of application, you
just need to extend the HttpKernel or write your own Kernel. But currently this
is not our intention.

To use the automatic form population, you need to use bootstrap (getbootstrap.com)
as your frontend framework. If you do not want to use it, you need to write your
own FormPopulation class and replace the existing with it. We will write a tutorial
for it in future. See the [mofifying components](#modifying).

### Why did we develop this framework
This framework was created in collaboration with Stefan Priebsch from the PHP
Consulting Company (thePHP.cc). It aims to provide a solid solution to create
a modular maintainable product out of our monolithic application. It should be
easy to understand for young and inexperienced developers and delivers solid
boundaries to develop new features in a maintainable way. A way of doing CQRS
(Command Query Responsibility Segregation) is embedded into it, but if you
do not need or want to use it, you can just ignore it (but we would not recommend it).

### What is PRG?
Post-Redirect-Get is a common web development design pattern.
See https://en.wikipedia.org/wiki/Post/Redirect/Get

### Why would i want to use this framework over others?
This framework doesn't aim to provide a solution for every
problem your application can possibly encounter, instead it tries to
provide an elegant way to implement a very specific task. Unlike
other frameworks it doesn't try to be a foundation for everything,
but rather a solid solution for your applications very specific PRG
needs. We also implement the majority of all other HTTP-Methods, so it is also easy to
use for a SPA as Backend or to implement a Web-Api.
 
It aims to be compatible with other frameworks for other tasks. 
The commands are re-usable and you should be able to inject any kind 
of action you already have into the framework using the command-interfaces. 

### Compatibility
PHP 7.1 and up for now. We will remove the support for the older PHP versions
very soon, so if you aim to stay on 7.1 or older, you should not use this framework.

## Quickstart<a name="quickstart"></a>
This section explains how you can get the framework up and running as
fast as possible.<br> 
**Note: Registering the framework into an existing application is out of 
scope of this guide, but shouldn't be a big problem, as we also did this.**

### Generating your application
Install with composer
```
php composer.phar require simovative/zeus 
```

And let the cli help you in setting up a default application
```
vendor/bin/zeus c:a Simovative\\Demo Demo
```
This will create a folder public with an index.php file and a folder "bundles" 
containing the ApplicationBundle and ApplicationKernel.

You might want to configure your webserver right away to test if this
worked. 

### Webserver config

#### Apache
Point your web-root to the $cwd/public

```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /index.php [NC,L,QSA]
```
#### Nginx
```
location / {
    try_files = $uri index.php?$args;
    root   /var/www/site/public;
    index  index.php;
}
```
#### PHP internal webserver
Just run the webserver and use the public/index.php as router script:
```
php -S localhost:8000 public/index.php
``` 
If everything worked you should see a basic setup page, telling you 
to add another bundle. 

### Generating a Bundle
```
vendor/bin/zeus c:b Test
```
Next, register the bundle, which we named "Test" in your Applications Kernel 
(in this case bundles/Application/DemoKernel.php)
```php
	protected function registerBundles(HttpRequestInterface $request) {
		$bundles = array();
		$bundles[] = new DemoApplicationBundle();
		$bundles[] = new \Simovative\Demo\Test\TestBundle();
		return $bundles;
	}
```
If everything worked the Router should take over and you'll see a 
page with the message:
```
Replace me with some serious content, please
```

### Generating a Page to display content (GET)
```
vendor/bin/zeus c:p Home Test
```

This will create two files in you bundles/Test/Page folder:
* HomePage.php: That's the page to show 
* HomePageFactoryMethods.txt: Move this code into your 
Bundle-Factory (TestFactory.php) to create the page from the router.
(The file can be deleted afterwards)

To create a route for this page add something similar to your GetRequestRouter
```
if ($request->getUrl() == '/test/home') {
	return $this->factory->createTestHomePage();
}
```

### Generating a Page with a form that will be submitted (GET)
```
vendor/bin/zeus c:f Login Test
```

This will create two files in you bundles/Test/Page folder:
* LoginForm.php 
* LoginFormFactoryMethods.txt 
They contain the same type of code as in a normal page and should be treated
the same way. Also use the similar code to create a route for it.

The only difference is, if the form is submitted to an command, and the 
validator invalidates it, the error messages and form content will be
automatically populated to the form. This happens in the CommandDispatcher
if you want to have a look at it.

### Generating a Command (POST)
```
vendor/bin/zeus c:c Login Test
```

This will create five files in you bundles/Test/Command folder:
* LoginCommand.php: The command object that holds all the data needed for execution.
* LoginCommandBuilder.php The command builder connects all the command classes 
and will be used by the command dispatcher to get the single components.
* LoginCommandHandler.php: The handler does the command execution.
* LoginCommandValidator.php: The validator checks the command request if the provided data is valid to create
a command and execute the handler. We recommend basic checks for required data and if the data types are correct and
data is in the correct format (e.g. like e-mail addresses). If the command can not be successfully executed because of
the current state of the application (e.g. some data in the database is missing), the command handler should return a
response that indicates that.
* LoginCommandFactoryMethods.txt: Move this code into your 
Bundle-Factory (TestFactory.php) to create all required components and access the 
builder creation method from the command router.

To create a route for this command add something similar to your CommandRouter
```
if ($request->getUrl() == '/test/login') {
	return $this->factory->createTestLoginCommandBuilder();
}
```

## General Structure<a name="structure"></a>

### Configuration
How you implement environments is up to you.
Doing so can be really simple, like writing a small ini file containing
information.
```php
<?php
/* public/index.php */
$basePath = __DIR__ . '/..';
require_once $basePath . '/vendor/autoload.php';

if (is_readable($basePath . '/config.ini')) {
    $config = parse_ini_file($basePath . '/config.ini');
} else {
    $config = array();
}

$masterFactory = new Simovative\Zeus\Dependency\MasterFactory(
	new \Simovative\Zeus\Configuration\Configuration($config, $basePath)
);

$kernel = new \Simovative\Skeleton\Application\SkeletonKernel($masterFactory);
$kernel->run(\Simovative\Zeus\Http\Request\HttpRequest::createFromGlobals());
```

A more sophisticated way would be fetching the configuration values from the environment.
```php
<?php
/* public/index.php */
$basePath = __DIR__ . '/..';
require_once $basePath . '/vendor/autoload.php';

$config = array(
    getenv('database_url'),
    getenv('redis_url'),
    getenv('queue_url'),
);

$masterFactory = new Simovative\Zeus\Dependency\MasterFactory(
	new \Simovative\Zeus\Configuration\Configuration($config, $basePath)
);

$kernel = new \Simovative\Skeleton\Application\SkeletonKernel($masterFactory);
$kernel->run(\Simovative\Zeus\Http\Request\HttpRequest::createFromGlobals());
```

However, if your environments grow very complex you'll need a more
sophisticated solution. The framework can handle everything as long
as the resulting configuration can be narrowed down to a
key-value array.

### MasterFactory

**All components of the framework are accessible through this factory**

Other factories can register in this factory using the Factory Interface.

**Conventions:** All components of the framework usually call the factorymethods  
with a "get" prefix for singletons and a "create" prefix if they explicitly require 
a new instance of a class. Thus, when injecting another factory into the master 
factory, methods not beginning with either "get" or "create" will be ignored. 

### Request

The framework has its own request classes that will be used to access the request
values.

### HTTPKernel

As mentioned previously, the framework does not ship a complete kernel,
instead you have to extend the abstract HttpKernel or implement your
own using the KernelInterface.
The default HttpKernel will:

* Handle the (optional) ApplicationState
* Register bundles
* Dispatch request
* Handle response

Example Implementation:
```php
<?php
namespace Simovative\Skeleton\Application;

use Simovative\Zeus\Http\HttpKernel;
use Simovative\Zeus\Http\Request\HttpRequestInterface;

/**
 * My Applications wonderful Kernel
 */
class Kernel extends HttpKernel {

	/**
	 * @inheritdoc
	 */
	protected function registerBundles(HttpRequestInterface $request) {
		return array(new ApplicationBundle());
	}

	/**
	 * @inheritdoc
	 */
	protected function getApplicationState() {
		return $this->getMasterFactory()->createApplicationState();
	}
}
```
In most cases everything you need is to provide the bundles to be loaded
and an ApplicationState (to handle sessions and such).

### Bundle

Bundles are a collection of commands, factories, routers, controllers and models. 
When your application grows bigger, you might want to split up the code in
different bundles, handling different parts. You should create at least
one bundle to be able to register routers and factories. Although, you
are not forced to, we highly encourage you to create one "ApplicationBundle",
that handles the very basic things of your application, like its core
elements, dependencies to other software packages, connections to
persistent storage systems, the ApplicationState and so on.
Everything else should be put in other bundles. For example a
"UserBundle" to handle everything related to user login, registration,
password changes, displaying profile pages and stuff like that. You should keep your bundles
as independent as possible from any other bundles, otherwise you will create a dependency hell.


**The registration of bundles happens in the kernel**

For bundles to work properly with the HttpKernel shipped by the framework,
they have to implement the _BundleInterface_.


### BundleFactory

A bundle is able to provide one or more factories that are being registered
into the MasterFactory.

Todo: Example/Code

### Commands
Whatever your application wants to do, it'll happen inside commands.
For the commands to be reusable by other frameworks and other entry-points
of your application (like APIs or CLI for cronjobs and so on) the
requests are separated in CommandRequest, Command, CommandValidation and
CommandHandler. All Commands are supposed to be handled inside a Bundle.

**Commands will be executed in Post-Requests only**

#### Request
Extend the abstract class CommandRequest. You have to implement two
Methods:
* createCommand: Takes a Request and assembles the Command
* createCommandHandler: Assembles the commandHandler using the bundle
Factory.

Todo: Example/Code

#### Command
The Command itself is basically just a container to store all information
needed in handling a specific task.

#### Validation
Takes the PostRequest and validates the parameters used by the command.

**The framework does not enforce validation.**

That means if you're too lazy to validate properly your application
 might end up easily exploitable.

#### Handler

The CommandHandler makes a Command executable with everything Transport-
Protocol related already abstracted. It consists of a single execute
Method taking the Command and returning a CommandResponse.

### Routing

The framework does handle POST, GET and all other type of HTTP-requests separately. There are
different RouterChains for all types in which routers can be registered. Bundles
will register only the appropriate chain depending on the request type.

Todo: Example

### Controller

**Controllers are used for Command-Requests only.** The CommandDispatcher
will use Controllers provided by the bundle to determine which Content
to display after the command execution. It is important for the
framework to know what to do in case a command doesn't execute or the validation fails.

### CommandResponses

A command response signals the application/bundle controller the result of a
command handler and lets the application react accordingly. Two default responses
are already implemented that should fit most cases (CommandSuccessResponse, 
CommandFailureResponse), but you can implement any kind of response yourself.

#### Content
#### HttpResponses & Locator
This HttpResponseLocator class will translate a content into its http representation. Not all kind of possible
content and responses are available in the framework. In case you need something that is not yet implemented, you
can use your own HttpResponseLocator class and implement any content with any kind of http response for it. For very 
special cases you can bypass this and directly return a http response in the router or controller.

### TemplateEngine
The default template engine that is used is smarty. You can replace it
with any your want in your application factory.

### ApplicationState
This is the equivalent of a session, but it can be anything that represents
the current state of your application.

## Implementing a login wall
A very common case for a application is having a login wall and all other functionality is hidden behind this. Zeus
does provide a very simple way of doing this. We recommend implementing the login functionality and the login check in
a separate bundle and registering this bundle directly after the application bundle in the "registerBundles" method of
your kernel.

```php
	protected function registerBundles(HttpRequestInterface $request) {
		return array(
            new ApplicationBundle(),
            new LoginBundle(),
            
            new OtherBundleThatNeedsLogin(),
        );
	}
```

The LoginBundle will implement a login check in its routers and return the login page if the user is not logged in.
In that way the routers of the other bundles will never get the request, because zeus does call the routers in the order
in which the bundles are registered. In this way you can do any kind of pre processing.

## Modifying Components <a name="modifying"></a>
These examples should make you familiar with the process of modifying
the behaviour of the framework with your application. **Note: It should
not be necessary to modify the sources of the framework in any way.**

### Create your own FormPopulation
Scenario: We do not want to use bootstrap as frontend framework but
		  we love the automatic form population
		  
TODO

### Example: Implementing Pre- and PostFilterChains
Scenario: We have a huge application with hundreds of routes and didn't
          care about localisation. Now the team decided to implement locales
          by adding en/ fr/ de/ es/ and so on at the beginning of the
          URI.
TODO
### Example: Changing the Router
TODO
### Example: Changing the Template Engine
TODO
### Example: Enforce generic ACL
TODO
## Extending your Application
TODO
### Add Language/Translation Support
TODO
### Add database support with doctrine
TODO
### Add a consul key/value store
TODO

## Build <a name="build"></a>
```
docker-compose up
```
### Run tests
```
php build/phpunit.phar --configuration tests/phpunit.xml
```