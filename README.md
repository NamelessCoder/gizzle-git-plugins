Gizzle: Git Plugins
===================

[![Build Status](https://img.shields.io/jenkins/s/https/jenkins.fluidtypo3.org/gizzle-git-plugins.svg?style=flat-square)](https://jenkins.fluidtypo3.org/job/gizzle-git-plugins/) [![Coverage Status](https://img.shields.io/coveralls/NamelessCoder/gizzle-git-plugins.svg?style=flat-square)](https://coveralls.io/r/NamelessCoder/gizzle-git-plugins?branch=master) [![Latest Stable Version](https://img.shields.io/packagist/v/NamelessCoder/gizzle-git-plugins.svg?style=flat-square)](https://packagist.org/packages/namelesscoder/gizzle-git-plugins) [![Total Downloads](https://img.shields.io/packagist/dt/NamelessCoder/gizzle-git-plugins.svg?style=flat-square)](https://packagist.org/packages/namelesscoder/gizzle-git-plugins)

Plugins to perform Git operations from a [Gizzle GitHub Webhook Listener](https://github.com/NamelessCoder/gizzle).

Settings
--------

The following `Settings.yml` file shows every possible setting for every plugin in this collection with sample values. **The values do not represent defaults - you must configure each plugin with at least the minimum required arguments of the corresponding Git command.

```yaml
NamelessCoder\GizzleGitPlugins:
  NamelessCoder\GizzleGitPlugins\GizzlePlugins\ClonePlugin:
    enabled: true
    directory: localpath
    branch: master
    repository: url-or-remote-name
    single: true
    depth: 50
    rebase: true
  NamelessCoder\GizzleGitPlugins\GizzlePlugins\PullPlugin:
    enabled: true
    directory: localpath
    repository: url-or-remote-name
    branch: master
    rebase: true
    depth: 1
  NamelessCoder\GizzleGitPlugins\GizzlePlugins\ResetPlugin:
    enabled: true
    directory: localpath
    hard: true
    head: shortname-like-HEAD^1-or-sha1
  NamelessCoder\GizzleGitPlugins\GizzlePlugins\PushPlugin:
    enabled: true
    repository: localpath
    branch: master
    checkout: true
    remote: originnameorurl
    head: remote branch name
  NamelessCoder\GizzleGitPlugins\GizzlePlugins\CommitPlugin:
    enabled: true
    repository: localpath
    branch: master
    checkout: true
    files: *
    add: true
  NamelessCoder\GizzleGitPlugins\GizzlePlugins\CheckoutPlugin:
    enabled: true
    repository: localpath
    branch: master
```
