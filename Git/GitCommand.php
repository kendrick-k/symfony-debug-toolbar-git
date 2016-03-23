<?php
namespace Kendrick\SymfonyDebugToolbarGit\Git;

interface GitCommand
{
    const GIT_LOG_MINUS_ONE = "git log -1";
    const GIT_STATUS = "git status";
    const GIT_CURRENT_BRANCH = "git rev-parse --abbrev-ref HEAD";
    const GIT_COMMIT_COUNT_HEAD = "git rev-list --count HEAD";

    //log last commit
    const GIT_EMAIL_LAST_COMMIT = "git log -1 --pretty=format:'%cn'";
    const GIT_AUTHOR_LAST_COMMIT = "git log -1 --pretty=format:'%ce'";
    const GIT_HASH_LAST_COMMIT = "git log -1 --pretty=format:' %H'";
    const GIT_MESSAGE_LAST_COMMIT = "git log -1 --pretty=format:' %s'";
    const GIT_ABBREVIATED_PARENT_HASHES = "git log -1 --pretty=%p";
    const GIT_COMMIT_DATE = "git log -1 --pretty=format:'%ad'";

}