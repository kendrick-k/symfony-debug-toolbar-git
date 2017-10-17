<?php

namespace Kendrick\SymfonyDebugToolbarGit\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Kendrick\SymfonyDebugToolbarGit\DependencyInjection
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
			->scalarNode('repository_commit_url')->end()
			->scalarNode('root_dir')->defaultValue(null)->end()
		;

		return $treeBuilder;
	}
}
