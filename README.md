Symfony Debug Toolbar Git
=========================

## Symfony debugbar add-on

### Get the latest git commit into Symfony debugbar

+ **Branch** : active branch
+ **Time since last commit** : total time in minutes + seconds since last commit at page generation  

Useful for local development but also for a continuous integration (CI) process on a development server

### Status information : mouse over

+ **Commit ID** : links to the commit URL on your repository (Github, Bitbucket..), base url is to set into repository_commit_url parameter
+ **Merge** : merge IDs if there is
+ **Author**
+ **Email** : email with active link
+ **Date** : full date of latest commit
+ **Commit message**

## Installation

### Register into AppKernel

app/AppKernel.php :

    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        [...]
        $bundles[] = new Kendrick\SymfonyDebugToolbarGit\SymfonyDebugToolbarGit();
    
### Parameters

app/config/config_dev.yml :

    symfony_debug_toolbar_git:
        repository_commit_url: ""