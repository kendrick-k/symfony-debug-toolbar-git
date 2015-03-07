<?php

namespace Kendrick\SymfonyDebugToolbarGit\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('symfony_debug_toolbar_git');

	    $rootNode
		    ->children()
	            ->scalarNode('repository_commit_url')
		    ->end()
	    ;

        return $treeBuilder;
    }
}
