<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Football\DataFixtures\Load01Users;
use Football\DataFixtures\Load02Leagues;
use Symfony\Bundle\WebServerBundle\Command\ServerStartCommand;
use Symfony\Bundle\WebServerBundle\Command\ServerStopCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Football\Model\JWT as JWTModel;
use Behatch\Context\RestContext;

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
    private $restContext;

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
                Load02Leagues::class,
            ]
        );
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $environment = $scope->getEnvironment();

        $this->restContext = $environment->getContext(RestContext::class);
    }

    /**
     * @Given I add a valid authentication header for user :arg1
     */
    public function iAddAValidAuthenticationHeaderForUser($userName)
    {
        $payload = new JWTModel\Body();

        $payload->name = $userName;

        $jwtService = $this->kernel->getContainer()->get('app.service.jwt');

        $tokenModel = $jwtService->generateToken($payload);

        $this->restContext->iAddHeaderEqualTo('Authorization', sprintf('Bearer %s', $tokenModel->token));
    }
}
