<?php

namespace Kendrick\SymfonyDebugToolbarGit\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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

		$this->addRepositoryCommitUrl($rootNode);
		$this->addDirGitLocal($rootNode);
		return $treeBuilder;
	}

	private function addRepositoryCommitUrl(ArrayNodeDefinition $node)
	{
		$node
			->children()
			->scalarNode('repository_commit_url')
			->end()
		;
	}


	private function addDirGitLocal(ArrayNodeDefinition $node)
	{
		$node
			->children()
			->scalarNode('repository_local_dir')
			->end();
	}
}
