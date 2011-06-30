<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\coverage;

use
	\mageekguy\atoum,
	\mageekguy\atoum\test,
	\mageekguy\atoum\mock,
	\mageekguy\atoum\template,
	\mageekguy\atoum\report\fields\runner\coverage
;

require_once(__DIR__ . '/../../../../../runner.php');

class html extends atoum\test
{
	public function testClass()
	{
		$this->assert
			->testedClass->isSubclassOf('\mageekguy\atoum\report\fields\runner\coverage\cli')
			->string(coverage\html::defaultPrompt)->isEqualTo('> ')
			->string(coverage\html::defaultAlternatePrompt)->isEqualTo('=> ')
		;
	}

	public function test__construct()
	{
		$field = new coverage\html($projectName = uniqid(), $templatesDirectory = uniqid(), $destinationDirectory = uniqid());

		$this->assert
			->string($field->getProjectName())->isEqualTo($projectName)
			->string($field->getTemplatesDirectory())->isEqualTo($templatesDirectory)
			->string($field->getDestinationDirectory())->isEqualTo($destinationDirectory)
			->object($field->getTemplateParser())->isInstanceOf('\mageekguy\atoum\template\parser')
			->object($field->getLocale())->isInstanceOf('\mageekguy\atoum\locale')
			->object($field->getAdapter())->isInstanceOf('\mageekguy\atoum\adapter')
			->string($field->getPrompt())->isEqualTo(coverage\html::defaultPrompt)
			->string($field->getAlternatePrompt())->isEqualTo(coverage\html::defaultAlternatePrompt)
			->variable($field->getCoverage())->isNull()
			->array($field->getSrcDirectories())->isEmpty()
		;

		$field = new coverage\html($projectName = uniqid(), $templatesDirectory = uniqid(), $destinationDirectory = uniqid(), $templateParser = new template\parser(), $adapter = new atoum\adapter(), $locale = new atoum\locale(), $prompt = uniqid(), $alternatePrompt = uniqid());

		$this->assert
			->string($field->getProjectName())->isEqualTo($projectName)
			->string($field->getTemplatesDirectory())->isEqualTo($templatesDirectory)
			->string($field->getDestinationDirectory())->isEqualTo($destinationDirectory)
			->object($field->getTemplateParser())->isEqualTo($templateParser)
			->object($field->getAdapter())->isIdenticalTo($adapter)
			->object($field->getLocale())->isIdenticalTo($locale)
			->string($field->getPrompt())->isEqualTo($prompt)
			->string($field->getAlternatePrompt())->isEqualTo($alternatePrompt)
			->variable($field->getCoverage())->isNull()
			->array($field->getSrcDirectories())->isEmpty()
		;
	}

	public function testSetAdapter()
	{
		$field = new coverage\html($projectName = uniqid(), $templatesDirectory = uniqid(), $destinationDirectory = uniqid());

		$this->assert
			->object($field->setAdapter($adapter = new atoum\adapter()))->isIdenticalTo($field)
			->object($field->getAdapter())->isIdenticalTo($adapter)
		;
	}

	public function testSetAlternatePrompt()
	{
		$field = new coverage\html($projectName = uniqid(), $templatesDirectory = uniqid(), $destinationDirectory = uniqid());

		$this->assert
			->object($field->setAlternatePrompt($alternatePrompt = uniqid()))->isIdenticalTo($field)
			->string($field->getAlternatePrompt())->isIdenticalTo($alternatePrompt)
			->object($field->setAlternatePrompt($alternatePrompt = rand(1, PHP_INT_MAX)))->isIdenticalTo($field)
			->string($field->getAlternatePrompt())->isIdenticalTo((string) $alternatePrompt)
		;
	}

	public function testSetTemplatesDirectory()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->object($field->setTemplatesDirectory($directory = uniqid()))->isIdenticalTo($field)
			->string($field->getTemplatesDirectory())->isEqualTo($directory)
			->object($field->setTemplatesDirectory($directory = rand(1, PHP_INT_MAX)))->isIdenticalTo($field)
			->string($field->getTemplatesDirectory())->isIdenticalTo((string) $directory)
		;
	}

	public function testSetDestinationDirectory()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->object($field->setDestinationDirectory($directory = uniqid()))->isIdenticalTo($field)
			->string($field->getDestinationDirectory())->isEqualTo($directory)
			->object($field->setDestinationDirectory($directory = rand(1, PHP_INT_MAX)))->isIdenticalTo($field)
			->string($field->getDestinationDirectory())->isIdenticalTo((string) $directory)
		;
	}

	public function testSetTemplateParser()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->object($field->setTemplateParser($templateParser = new template\parser()))->isIdenticalTo($field)
			->object($field->getTemplateParser())->isIdenticalTo($templateParser)
		;
	}

	public function testSetProjectName()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->object($field->setProjectName($projectName = uniqid()))->isIdenticalTo($field)
			->string($field->getProjectName())->isIdenticalTo($projectName)
			->object($field->setProjectName($projectName = rand(1, PHP_INT_MAX)))->isIdenticalTo($field)
			->string($field->getProjectName())->isIdenticalTo((string) $projectName)
		;
	}

	public function testGetDestinationDirectoryIterator()
	{
		$field = new coverage\html(uniqid(), uniqid(), __DIR__);

		$this->assert
			->object($recursiveIteratorIterator = $field->getDestinationDirectoryIterator())->isInstanceOf('\recursiveIteratorIterator')
			->object($recursiveDirectoryIterator = $recursiveIteratorIterator->getInnerIterator())->isInstanceOf('\recursiveDirectoryIterator')
			->string($recursiveDirectoryIterator->current()->getPathInfo()->getPathname())->isEqualTo(__DIR__)
		;
	}

	public function testGetSrcDirectoryIterators()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->array($field->getSrcDirectoryIterators())->isEmpty()
		;

		$field->addSrcDirectory($directory = __DIR__);

		$this->assert
			->array($iterators = $field->getSrcDirectoryIterators())->isEqualTo(array(new \recursiveIteratorIterator(new atoum\iterators\filters\recursives\closure(new \recursiveDirectoryIterator($directory)))))
			->array(current($iterators)->getClosures())->isEmpty()
		;

		$field->addSrcDirectory($directory, $closure = function() {});

		$this->assert
			->array($iterators = $field->getSrcDirectoryIterators())->isEqualTo(array(new \recursiveIteratorIterator(new atoum\iterators\filters\recursives\closure(new \recursiveDirectoryIterator($directory)))))
			->array(current($iterators)->getClosures())->isEqualTo(array($closure))
		;

		$field->addSrcDirectory($otherDirectory = __DIR__ . '/..', $otherClosure = function() {});

		$this->assert
			->array($iterators = $field->getSrcDirectoryIterators())->isEqualTo(array(
						new \recursiveIteratorIterator(new atoum\iterators\filters\recursives\closure(new \recursiveDirectoryIterator($directory))),
						new \recursiveIteratorIterator(new atoum\iterators\filters\recursives\closure(new \recursiveDirectoryIterator($otherDirectory)))
				)
			)
			->array(current($iterators)->getClosures())->isEqualTo(array($closure))
			->array(next($iterators)->getClosures())->isEqualTo(array($otherClosure))
		;

		$field->addSrcDirectory($otherDirectory, $anOtherClosure = function() {});

		$this->assert
			->array($iterators = $field->getSrcDirectoryIterators())->isEqualTo(array(
						new \recursiveIteratorIterator(new atoum\iterators\filters\recursives\closure(new \recursiveDirectoryIterator($directory))),
						new \recursiveIteratorIterator(new atoum\iterators\filters\recursives\closure(new \recursiveDirectoryIterator($otherDirectory)))
				)
			)
			->array(current($iterators)->getClosures())->isEqualTo(array($closure))
			->array(next($iterators)->getClosures())->isEqualTo(array($otherClosure, $anOtherClosure))
		;
	}

	public function testCleanDestinationDirectory()
	{
		$this
			->mock('\mageekguy\atoum\report\fields\runner\coverage\html')
			->mock('\splFileInfo')
		;

		$field = new mock\mageekguy\atoum\report\fields\runner\coverage\html(uniqid(), uniqid(), $destinationDirectoryPath = uniqid(), null, $adapter = new test\adapter());

		$adapter->rmdir = function() {};
		$adapter->unlink = function() {};

		$inode11Controller = new mock\controller();
		$inode11Controller->__construct = function() {};
		$inode11Controller->isDir = false;
		$inode11Controller->getPathname = $inode11Path = uniqid();

		$inode11 = new mock\splFileInfo(uniqid(), $inode11Controller);

		$inode12Controller = new mock\controller();
		$inode12Controller->__construct = function() {};
		$inode12Controller->isDir = false;
		$inode12Controller->getPathname = $inode12Path = uniqid();

		$inode12 = new mock\splFileInfo(uniqid(), $inode12Controller);

		$inode1Controller = new mock\controller();
		$inode1Controller->__construct = function() {};
		$inode1Controller->isDir = true;
		$inode1Controller->getPathname = $inode1Path = uniqid();

		$inode1 = new mock\splFileInfo(uniqid(), $inode1Controller);

		$inode2Controller = new mock\controller();
		$inode2Controller->__construct = function() {};
		$inode2Controller->isDir = false;
		$inode2Controller->getPathname = $inode2Path = uniqid();

		$inode2 = new mock\splFileInfo(uniqid(), $inode2Controller);

		$inode31Controller = new mock\controller();
		$inode31Controller->__construct = function() {};
		$inode31Controller->isDir = false;
		$inode31Controller->getPathname = $inode31Path = uniqid();

		$inode31 = new mock\splFileInfo(uniqid(), $inode31Controller);

		$inode32Controller = new mock\controller();
		$inode32Controller->__construct = function() {};
		$inode32Controller->isDir = false;
		$inode32Controller->getPathname = $inode32Path = uniqid();

		$inode32 = new mock\splFileInfo(uniqid(), $inode32Controller);

		$inode3Controller = new mock\controller();
		$inode3Controller->__construct = function() {};
		$inode3Controller->isDir = true;
		$inode3Controller->getPathname = $inode3Path = uniqid();

		$inode3 = new mock\splFileInfo(uniqid(), $inode3Controller);

		$inodeController = new mock\controller();
		$inodeController->__construct = function() {};
		$inodeController->isDir = true;
		$inodeController->getPathname = $destinationDirectoryPath;

		$inode = new mock\splFileInfo(uniqid(), $inodeController);

		$field->getMockController()->getDestinationDirectoryIterator = array(
			$inode11,
			$inode12,
			$inode1,
			$inode2,
			$inode31,
			$inode32,
			$inode3,
			$inode
		);

		$this->assert
			->object($field->cleanDestinationDirectory())->isIdenticalTo($field)
			->adapter($adapter)
				->call('unlink', array($inode11Path))
				->call('unlink', array($inode12Path))
				->call('rmdir', array($inode1Path))
				->call('unlink', array($inode2Path))
				->call('unlink', array($inode31Path))
				->call('unlink', array($inode32Path))
				->call('rmdir', array($inode3Path))
				->notCall('rmdir', array($destinationDirectoryPath))
		;
	}

	public function testAddSrcDirectory()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->object($field->addSrcDirectory($srcDirectory = uniqid()))->isIdenticalTo($field)
			->array($field->getSrcDirectories())->isEqualTo(array($srcDirectory => array()))
			->object($field->addSrcDirectory($srcDirectory))->isIdenticalTo($field)
			->array($field->getSrcDirectories())->isEqualTo(array($srcDirectory => array()))
			->object($field->addSrcDirectory($otherSrcDirectory = rand(1, PHP_INT_MAX)))->isIdenticalTo($field)
			->array($field->getSrcDirectories())->isIdenticalTo(array($srcDirectory => array(), (string) $otherSrcDirectory => array()))
			->object($field->addSrcDirectory($srcDirectory, $closure = function() {}))->isIdenticalTo($field)
			->array($field->getSrcDirectories())->isIdenticalTo(array($srcDirectory => array($closure), (string) $otherSrcDirectory => array()))
			->object($field->addSrcDirectory($srcDirectory, $otherClosure = function() {}))->isIdenticalTo($field)
			->array($field->getSrcDirectories())->isIdenticalTo(array($srcDirectory => array($closure, $otherClosure), (string) $otherSrcDirectory => array()))
		;
	}

	public function testSetRootUrl()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->object($field->setRootUrl($rootUrl = uniqid()))->isIdenticalTo($field)
			->string($field->getRootUrl())->isIdenticalTo($rootUrl . '/')
			->object($field->setRootUrl($rootUrl = rand(1, PHP_INT_MAX)))->isIdenticalTo($field)
			->string($field->getRootUrl())->isIdenticalTo((string) $rootUrl . '/')
			->object($field->setRootUrl(($rootUrl = uniqid()) . '/'))->isIdenticalTo($field)
			->string($field->getRootUrl())->isIdenticalTo($rootUrl . '/')
		;
	}

	public function testSetWithRunner()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->object($field->setWithRunner($runner = new atoum\runner()))->isIdenticalTo($field)
			->variable($field->getCoverage())->isNull()
		;

		$this->assert
			->object($field->setWithRunner($runner, atoum\runner::runStop))->isIdenticalTo($field)
			->object($field->getCoverage())->isIdenticalTo($runner->getScore()->getCoverage())
		;
	}

	public function testSetReflectionClassInjector()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->mock('\reflectionClass');

		$reflectionClassController = new mock\controller();
		$reflectionClassController->__construct = function() {};

		$reflectionClass = new mock\reflectionClass(uniqid(), $reflectionClassController);

		$this->assert
			->object($field->setReflectionClassInjector(function($class) use ($reflectionClass) { return $reflectionClass; }))->isIdenticalTo($field)
			->object($field->getReflectionClass(uniqid()))->isIdenticalTo($reflectionClass)
			->exception(function() use ($field) {
						$field->setReflectionClassInjector(function() {});
					}
				)
				->isInstanceOf('\mageekguy\atoum\exceptions\logic\invalidArgument')
				->hasMessage('Reflection class injector must take one argument')
		;
	}

	public function testGetReflectionClass()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->object($field->getReflectionClass(__CLASS__))->isInstanceOf('\reflectionClass')
			->string($field->getReflectionClass(__CLASS__)->getName())->isEqualTo(__CLASS__)
		;

		$field->setReflectionClassInjector(function($class) {});

		$this->assert
			->exception(function() use ($field) {
						$field->getReflectionClass(uniqid());
					}
				)
				->isInstanceOf('\mageekguy\atoum\exceptions\runtime\unexpectedValue')
				->hasMessage('Reflection class injector must return a \reflectionClass instance')
		;
	}

	public function test__toString()
	{
		$field = new coverage\html(uniqid(), uniqid(), uniqid());

		$this->assert
			->castToString($field)->isEqualTo('> Code coverage: unknown.' . PHP_EOL)
		;

		$this
			->mock('\mageekguy\atoum\score')
			->mock('\mageekguy\atoum\score\coverage')
			->mock('\mageekguy\atoum\runner')
			->mock('\mageekguy\atoum\template')
			->mock('\mageekguy\atoum\template\tag')
			->mock('\mageekguy\atoum\template\parser')
			->mock('\reflectionClass')
			->mock('\reflectionMethod')
			->mock($this->getTestedClassName())
		;

		$coverage = new mock\mageekguy\atoum\score\coverage();
		$coverageController = $coverage->getMockController();
		$coverageController->count = rand(1, PHP_INT_MAX);
		$coverageController->getClasses = array(
			$className = uniqid() => $classFile = uniqid()
		);
		$coverageController->getMethods = array(
			$className =>
				array(
					$method1Name = uniqid() =>
						array(
							5 => 1,
							6 => 1,
							7 => -1,
							8 => 1,
							9 => -2
						),
					$method3Name = uniqid() =>
						array(
							10 => -2
						),
					$method4Name = uniqid() =>
						array(
							11 => 1,
							12 => -2
						)
				)
		);
		$coverageController->getValue = $coverageValue = rand(1, 10) / 10;
		$coverageController->getValueForClass = $classCoverageValue = rand(1, 10) / 10;
		$coverageController->getValueForMethod = $methodCoverageValue = rand(1, 10) / 10;

		$score = new mock\mageekguy\atoum\score();
		$score->getMockController()->getCoverage = $coverage;

		$runner = new mock\mageekguy\atoum\runner();
		$runner->getMockController()->getScore = $score;

		$classCoverageTemplate = new mock\mageekguy\atoum\template\tag('classCoverage');
		$classCoverageTemplate
			->addChild($classCoverageAvailableTemplate = new mock\mageekguy\atoum\template\tag('classCoverageAvailable'))
		;

		$indexTemplate = new mock\mageekguy\atoum\template();
		$indexTemplate
			->addChild($coverageAvailableTemplate = new mock\mageekguy\atoum\template\tag('coverageAvailable'))
			->addChild($classCoverageTemplate)
		;

		$indexTemplateController = $indexTemplate->getMockController();
		$indexTemplateController->__set = function() {};
		$indexTemplateController->build = $buildOfIndexTemplate = uniqid();

		$methodTemplate = new mock\mageekguy\atoum\template();
		$methodTemplateController = $methodTemplate->getMockController();
		$methodTemplateController->__set = function() {};


		$lineTemplate = new mock\mageekguy\atoum\template\tag('line');
		$lineTemplateController = $lineTemplate->getMockController();
		$lineTemplateController->__set = function() {};

		$coveredLineTemplate = new mock\mageekguy\atoum\template\tag('coveredLine');
		$coveredLineTemplateController = $coveredLineTemplate->getMockController();
		$coveredLineTemplateController->__set = function() {};

		$notCoveredLineTemplate = new mock\mageekguy\atoum\template\tag('notCoveredLine');
		$notCoveredLineTemplateController = $notCoveredLineTemplate->getMockController();
		$notCoveredLineTemplateController->__set = function() {};

		$sourceFileTemplate = new mock\mageekguy\atoum\template\tag('sourceFile');
		$sourceFileTemplateController = $sourceFileTemplate->getMockController();
		$sourceFileTemplateController->__set = function() {};

		$sourceFileTemplate
			->addChild($lineTemplate)
			->addChild($coveredLineTemplate)
			->addChild($notCoveredLineTemplate)
		;

		$methodCoverageAvailableTemplate = new mock\mageekguy\atoum\template\tag('methodCoverageAvailable');

		$methodTemplate = new mock\mageekguy\atoum\template\tag('method');
		$methodTemplate->addChild($methodCoverageAvailableTemplate);

		$methodsTemplate = new mock\mageekguy\atoum\template\tag('methods');
		$methodsTemplate->addChild($methodTemplate);

		$classTemplate = new mock\mageekguy\atoum\template();
		$classTemplateController = $classTemplate->getMockController();
		$classTemplateController->__set = function() {};

		$classTemplate
			->addChild($methodsTemplate)
			->addChild($sourceFileTemplate)
		;

		$reflectedClassController = new mock\controller();
		$reflectedClassController->__construct = function() {};
		$reflectedClassController->getName = $className;

		$reflectedClass = new mock\reflectionClass(uniqid(), $reflectedClassController);

		$otherReflectedClassController = new mock\controller();
		$otherReflectedClassController->__construct = function() {};
		$otherReflectedClassController->getName = uniqid();

		$otherReflectedClass = new mock\reflectionClass(uniqid(), $otherReflectedClassController);

		$reflectedMethod1Controller = new mock\controller();
		$reflectedMethod1Controller->__construct = function() {};
		$reflectedMethod1Controller->getName = $method1Name;
		$reflectedMethod1Controller->isAbstract = false;
		$reflectedMethod1Controller->getDeclaringClass = $reflectedClass;
		$reflectedMethod1Controller->getStartLine = 5;

		$reflectedMethod1 = new mock\reflectionMethod(uniqid(), uniqid(), $reflectedMethod1Controller);

		$reflectedMethod2Controller = new mock\controller();
		$reflectedMethod2Controller->__construct = function() {};
		$reflectedMethod2Controller->getName = $method2Name = uniqid();
		$reflectedMethod2Controller->isAbstract = false;
		$reflectedMethod2Controller->getDeclaringClass = $otherReflectedClass;
		$reflectedMethod2Controller->getStartLine = 5;

		$reflectedMethod2 = new mock\reflectionMethod(uniqid(), uniqid(), $reflectedMethod2Controller);

		$reflectedMethod3Controller = new mock\controller();
		$reflectedMethod3Controller->__construct = function() {};
		$reflectedMethod3Controller->getName = $method3Name;
		$reflectedMethod3Controller->isAbstract = true;
		$reflectedMethod3Controller->getDeclaringClass = $reflectedClass;
		$reflectedMethod3Controller->getStartLine = 10;

		$reflectedMethod3 = new mock\reflectionMethod(uniqid(), uniqid(), $reflectedMethod3Controller);

		$reflectedMethod4Controller = new mock\controller();
		$reflectedMethod4Controller->__construct = function() {};
		$reflectedMethod4Controller->getName = $method4Name;
		$reflectedMethod4Controller->isAbstract = false;
		$reflectedMethod4Controller->getDeclaringClass = $reflectedClass;
		$reflectedMethod4Controller->getStartLine = 11;

		$reflectedMethod4 = new mock\reflectionMethod(uniqid(), uniqid(), $reflectedMethod4Controller);

		$reflectedClassController->getMethods = array($reflectedMethod1, $reflectedMethod2, $reflectedMethod3, $reflectedMethod4);

		$templateParser = new mock\mageekguy\atoum\template\parser();

		$field = new mock\mageekguy\atoum\report\fields\runner\coverage\html($projectName = uniqid(), $templatesDirectory = uniqid(), $destinationDirectory = uniqid(), $templateParser, $adapter = new test\adapter());
		$fieldController = $field->getMockController();
		$fieldController->cleanDestinationDirectory = function() {};
		$fieldController->getReflectionClass = $reflectedClass;

		$field
			->setWithRunner($runner, atoum\runner::runStop)
			->setRootUrl($rootUrl = uniqid())
		;

		$templateParserController = $templateParser->getMockController();
		$templateParserController->parseFile = function($path) use ($templatesDirectory, $indexTemplate, $classTemplate){
			switch ($path)
			{
				case $templatesDirectory . '/index.tpl':
					return $indexTemplate;

				case $templatesDirectory . '/class.tpl':
					return $classTemplate;
			}
		};

		$adapter->mkdir = function() {};
		$adapter->file_put_contents = function() {};
		$adapter->filemtime = $filemtime = time();
		$adapter->fopen = $classResource = uniqid();
		$adapter->fgets = false;
		$adapter->fgets[1] = $line1 = uniqid();
		$adapter->fgets[2] = $line2 = uniqid();
		$adapter->fgets[3] = $line3 = uniqid();
		$adapter->fgets[4] = $line4 = uniqid();
		$adapter->fgets[5] = $line5 = uniqid();
		$adapter->fgets[6] = $line6 = uniqid();
		$adapter->fgets[7] = $line7 = uniqid();
		$adapter->fgets[8] = $line8 = uniqid();
		$adapter->fgets[9] = $line9 = uniqid();
		$adapter->fgets[10] = $line10 = uniqid();
		$adapter->fgets[11] = $line11 = uniqid();
		$adapter->fgets[12] = $line12 = uniqid();
		$adapter->fgets[13] = $line13 = uniqid();
		$adapter->fgets[14] = $line14 = uniqid();
		$adapter->fgets[15] = $line15 = uniqid();
		$adapter->fclose = function() {};
		$adapter->copy = function() {};

		$this->assert
			->object($field->getCoverage())->isIdenticalTo($coverage)
			->castToString($field)->isIdenticalTo('> ' . sprintf($field->getLocale()->_('Code coverage: %3.2f%%.'),  round($coverageValue * 100, 2)) . PHP_EOL . '=> Details of code coverage are available at ' . $rootUrl . '/.' . PHP_EOL)
			->mock($coverage)->call('count')
			->mock($field)
				->call('cleanDestinationDirectory')
			->mock($coverage)
				->call('count')
				->call('getClasses')
				->call('getMethods')
				->call('getValueForClass', array($className))
				->call('getValueForMethod', array($className, $method1Name))
				->notCall('getValueForMethod', array($className, $method2Name))
				->notCall('getValueForMethod', array($className, $method3Name))
				->call('getValueForMethod', array($className, $method4Name))
			->mock($templateParser)
				->call('parseFile', array($templatesDirectory . '/index.tpl', null))
				->call('parseFile', array($templatesDirectory . '/class.tpl', null))
			->mock($indexTemplate)
				->call('__set', array('projectName', $projectName))
				->call('__set', array('rootUrl', $rootUrl . '/'))
				->call('__get', array('coverageAvailable'))
				->call('__get', array('classCoverage'))
			->mock($coverageAvailableTemplate)
				->call('build', array(array('coverageValue' => round($coverageValue * 100, 2))))
			->mock($classTemplate)
				->call('__set', array('rootUrl', $rootUrl . '/'))
				->call('__set', array('projectName' , $projectName))
				->call('__set', array('className', $className))
				->call('__get', array('methods'))
				->call('__get', array('sourceFile'))
				->call('build')
			->mock($classCoverageTemplate)
				->call('__set', array('className', $className))
				->call('__set', array('classUrl', str_replace('\\', '/', $className) . coverage\html::htmlExtensionFile))
				->call('build')
			->mock($classCoverageAvailableTemplate)
				->call('build', array(array('classCoverageValue' => round($classCoverageValue * 100, 2))))
				->call('resetData')
			->mock($methodsTemplate)
				->call('build')
				->call('resetData')
			->mock($methodTemplate)
				->call('build')
				->call('resetData')
			->mock($methodCoverageAvailableTemplate)
				->call('__set', array('methodName', $method1Name))
				->notCall('__set', array('methodName', $method2Name))
				->notCall('__set', array('methodName', $method3Name))
				->call('__set', array('methodName', $method4Name))
				->call('__set', array('methodCoverageValue', round($methodCoverageValue * 100, 2)))
				->call('build')
				->call('resetData')
			->mock($lineTemplate)
				->call('__set', array('code', $line1))
				->call('__set', array('code', $line2))
				->call('__set', array('code', $line3))
				->call('__set', array('code', $line4))
				->call('__set', array('code', $line9))
				->call('__set', array('code', $line12))
			->mock($coveredLineTemplate)
				->call('__set', array('code', $line5))
				->call('__set', array('code', $line6))
				->call('__set', array('code', $line8))
				->call('__set', array('code', $line11))
			->mock($notCoveredLineTemplate)
				->call('__set', array('code', $line7))
			->adapter($adapter)
				->call('file_put_contents', array($destinationDirectory . '/index.html', $buildOfIndexTemplate))
				->call('fopen', array($classFile, 'r'))
				->call('fgets', array($classResource))
				->call('fclose', array($classResource))
		;
	}
}

?>
