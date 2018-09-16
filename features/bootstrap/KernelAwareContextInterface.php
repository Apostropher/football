<?php

use Behat\Symfony2Extension\Context\KernelAwareContext as Symfony2ExtensionKernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;

interface KernelAwareContextInterface extends Symfony2ExtensionKernelAwareContext
{
    public function getKernel(): KernelInterface;
}
