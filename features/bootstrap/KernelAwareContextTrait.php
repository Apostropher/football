<?php

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as SqliteDriver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

trait KernelAwareContextTrait
{
    public function buildAllSchema()
    {
        $container = $this->getKernel()->getContainer();

        $entityManagers = $container->get('doctrine')->getManagers();

        foreach ($entityManagers as $name => $em) {
            $metadata = $em->getMetadataFactory()->getAllMetadata();
            if (!empty($metadata)) {
                if ($this->hasDriver($em, SqliteDriver::class)) {
                    $backupPath = $this->createBackupName($metadata);
                    $actualPath = $em->getConnection()->getParams()['path'];

                    if (file_exists($backupPath)) {
                        $this->backupDatabase($backupPath, $actualPath);

                        return;
                    }

                    $this->buildSingleSchema($em, $metadata);
                    $this->backupDatabase($actualPath, $backupPath);
                }

                $this->buildSingleSchema($em, $metadata);
            }
        }
    }

    public function loadFixtures($emName, array $fixtureClasses)
    {
        $em = $this->getKernel()->getContainer()->get('doctrine')->getManager($emName);

        if ($this->hasDriver($em, SqliteDriver::class)) {
            $metadata = $em->getMetadataFactory()->getAllMetadata();

            $backupPath = $this->createBackupName($metadata, $fixtureClasses);
            $actualPath = $em->getConnection()->getParams()['path'];

            $this->loadFixturesForEntityManager($em, $fixtureClasses);
            $this->backupDatabase($actualPath, $backupPath);
        } else {
            $this->loadFixturesForEntityManager($em, $fixtureClasses);
        }
    }

    protected function createBackupName($metadata, $fixturesClasses = [])
    {
        return $this->getKernel()->getCacheDir().'/test_'.md5(serialize($metadata).serialize($fixturesClasses)).'.db';
    }

    protected function loadFixturesForEntityManager(EntityManager $em, array $fixtureClasses)
    {
        $loader = new ContainerAwareLoader($this->getKernel()->getContainer());
        $this->loadFixtureClasses($loader, $fixtureClasses);

        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);
        $executor->purge();
        $executor->execute($loader->getFixtures(), true);
    }

    protected function buildSingleSchema(EntityManager $em, $metadata)
    {
        $tool = new SchemaTool($em);
        $tool->dropSchema($metadata);
        $tool->createSchema($metadata);
    }

    protected function backupDatabase($source, $destination)
    {
        $permission = 0775;
        copy($source, $destination);
        chmod($source, $permission);
        chmod($destination, $permission);
    }

    protected function hasDriver(EntityManager $em, $driverClass)
    {
        $conn = $em->getConnection();

        return $conn->getDriver() instanceof $driverClass;
    }

    private function loadFixtureClasses(ContainerAwareLoader $loader, $classNames)
    {
        foreach ($classNames as $className) {
            $fixture = new $className();

            if (!$loader->hasFixture($fixture)) {
                $loader->addFixture(new $className());
            }
        }
    }
}
