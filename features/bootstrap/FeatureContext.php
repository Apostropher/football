<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Football\DataFixtures\Load01Users;
use Symfony\Bundle\WebServerBundle\Command\ServerStartCommand;
use Symfony\Bundle\WebServerBundle\Command\ServerStopCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext implements KernelAwareContextInterface, SnippetAcceptingContext
{
    use KernelAwareContextTrait;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    private $filesPath;

    public function __construct($filesPath)
    {
        $this->filesPath = $filesPath;
    }

    public function setKernel(KernelInterface $kernelInterface)
    {
        $this->kernel = $kernelInterface;
    }

    public function getKernel(): KernelInterface
    {
        return $this->kernel;
    }

    /**
     * @BeforeSuite
     */
    public static function prepare(BeforeSuiteScope $scope)
    {
        $commandTester = new CommandTester(new ServerStartCommand());
        $commandTester->execute([]);
    }

    /** @AfterSuite */
    public static function teardown(AfterSuiteScope $scope)
    {
        $commandTester = new CommandTester(new ServerStopCommand());
        $commandTester->execute([]);
    }

    /** @BeforeScenario */
    public function before(BeforeScenarioScope $scope)
    {
        $this->buildAllSchema();
        $this->loadFixtures(
            'football',
            [
                Load01Users::class,
            ]
        );
    }
}
