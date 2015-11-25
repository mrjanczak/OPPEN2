<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	/*
	public function getRootDir()
	{
		if (isset($_ENV['SYMFONY_ENV']) && $_ENV['SYMFONY_ENV'] == 'prod') {
			// Workaround to avoid problem with the slug of heroku
			return '/app/app';
		}
		return parent::getRootDir();
	}
	*/

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
            new Propel\PropelBundle\PropelBundle(),
            new FOS\UserBundle\FOSUserBundle(),          
            new Bmatzner\JQueryBundle\BmatznerJQueryBundle(),
            new Bmatzner\JQueryUIBundle\BmatznerJQueryUIBundle(),
            new PunkAve\FileUploaderBundle\PunkAveFileUploaderBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new Oppen\ProjectBundle\OppenProjectBundle(),            
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
