<?php
namespace Kendrick\SymfonyDebugToolbarGit\Git;
interface GitCommand
{
    const GIT_LOG_MINUS_ONE = 'git log -1';
    const GIT_STATUS = 'git status';
    const GIT_CURRENT_BRANCH = 'git rev-parse --abbrev-ref HEAD';
}