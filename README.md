Symfony Debug Toolbar Git
=========================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/08698266-b800-4ca4-bb20-8ceb65b3f31b/big.png)](https://insight.sensiolabs.com/projects/08698266-b800-4ca4-bb20-8ceb65b3f31b)

[![Latest Stable Version](https://poser.pugx.org/kendrick/symfony-debug-toolbar-git/v/stable.svg)](https://packagist.org/packages/kendrick/symfony-debug-toolbar-git) [![Total Downloads](https://poser.pugx.org/kendrick/symfony-debug-toolbar-git/downloads.svg)](https://packagist.org/packages/kendrick/symfony-debug-toolbar-git) [![Latest Unstable Version](https://poser.pugx.org/kendrick/symfony-debug-toolbar-git/v/unstable.svg)](https://packagist.org/packages/kendrick/symfony-debug-toolbar-git) [![License](https://poser.pugx.org/kendrick/symfony-debug-toolbar-git/license.svg)](https://packagist.org/packages/kendrick/symfony-debug-toolbar-git)

[![Monthly Downloads](https://poser.pugx.org/kendrick/symfony-debug-toolbar-git/d/monthly.png)](https://packagist.org/packages/kendrick/symfony-debug-toolbar-git) [![Daily Downloads](https://poser.pugx.org/kendrick/symfony-debug-toolbar-git/d/daily.png)](https://packagist.org/packages/kendrick/symfony-debug-toolbar-git)

[![Travis CI](https://travis-ci.org/kendrick-k/symfony-debug-toolbar-git.svg?branch=develop)](https://travis-ci.org/kendrick-k/symfony-debug-toolbar-git) [![Scrutinizer CI](https://scrutinizer-ci.com/g/kendrick-k/symfony-debug-toolbar-git/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kendrick-k/symfony-debug-toolbar-git/) [![Scrutinizer CI](https://scrutinizer-ci.com/g/kendrick-k/symfony-debug-toolbar-git/badges/build.png?b=master)](https://scrutinizer-ci.com/g/kendrick-k/symfony-debug-toolbar-git/)

## Symfony toolbar add-on

### Get the latest git commit into Symfony debug toolbar

And visualize quickly the latest commit into your repository by clicking on the **Commit ID**.

![SymfonyDebugToolbarGit](SymfonyDebugToolbarGit.png "SymfonyDebugToolbarGit")

![SymfonyDebugToolbarGit2.8](symfony_toolbar_2-8.png "SymfonyDebugToolbarGit2.8")

Information displayed :

+ **Branch** : active branch
+ **Time since last commit** : time since last commit at page generation  

1. less than one hour : minutes + seconds | colored in green, then in red :
2. more than one hour : hour(s)
3. more than 24h : count in days
4. more than 1 month : count in months

Useful for local development but also for a continuous integration (CI) process on a development server.

If no git repository have been initiated, there will be no display into the toolbar.

### Status information : mouse over

Information displayed :

+ **Commit ID** : links to the commit URL on your repository (Github, Bitbucket..), base url is to set into repository_commit_url parameter
+ **Merge** : merge IDs if there is
+ **Author**
+ **Email** : email with active link
+ **Date** : full date of latest commit
+ **Commit message**

## Installation

### Composer

    composer require kendrick/symfony-debug-toolbar-git

In your composer.json :

    "require": {
        [...]
        "kendrick/symfony-debug-toolbar-git": "1.*"

Install or update :
    
Terminal alias or Command Line Tools Console of PHPStorm

    c install
    c update

No alias

    php composer.phar install
    php composer.phar update

### PHPStorm shortcut

Menu : Tools > Composer > Add dependency, then type part of the package name : kendrick/symfony-debug-toolbar-git.

### Register into AppKernel

app/AppKernel.php :

    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        [...]
        $bundles[] = new Kendrick\SymfonyDebugToolbarGit\SymfonyDebugToolbarGit();
    
### Parameters

    # app/config/config_dev.yml
    symfony_debug_toolbar_git:
        repository_commit_url: " " #default value  
        repository_local_dir:  /   #default value

 
####  repository_commit_url example in usage


    ...
    #example 1: github repository 
        repository_local_dir: https://github.com/Rafaelgfirmino/project/commit/
    #example 1: gitlab repository 
        repository_local_dir: https://gitlab.com/rafaelgfirmino/project/commit/
    #example 1: bit repository 
        repository_local_dir: https://bitbucket.org/team/project/commits/



####  repository_local_dir example in usage
>**Note:**
>**repository_local_dir**  initial path is **app/..**

        ...
    # example 1: this is path default  =  app/../
        repository_local_dir:  /  

    #example 2: this is path to src   = app/.../src
        repository_local_dir: /src
        
    #example 3: this is path to bundle  =  app/../src/package/bundleName
        repository_local_dir: /src/package/bundleName
    
    
>**Note:**
>**Case not exist git directory you will see this message
    
![GitDirectoryNotFound](symfony_toolbar_erro_dir.png "Git directory not found")