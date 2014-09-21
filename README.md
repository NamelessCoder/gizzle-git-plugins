Gizzle: Git Plugins
===================

Plugins to perform Git operations from a [Gizzle GitHub Webhook Listener](https://github.com/NamelessCoder/gizzle).

Settings
--------

The following `Settings.yml` file shows every possible setting for every plugin in this collection with sample values. **The values do not represent defaults - you must configure each plugin with at least the minimum required arguments of the corresponding Git command.

```yaml
NamelessCoder\\GizzleGitPlugins:
  NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\PullPlugin:
    enabled: true
    repository: localpath
    branch: master
    checkout: true
    reset: true
    hard: true
  NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\PushPlugin:
    enabled: true
    repository: localpath
    branch: master
    checkout: true
    remote: originnameorurl
    head: remote branch name
  NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\CommitPlugin:
    enabled: true
    repository: localpath
    branch: master
    checkout: true
    files: *
    add: true
  NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\ClonePlugin:
    enabled: true
    repository: url
    path: localpath
    branch: master
    depth: 1
  NamelessCoder\\GizzleGitPlugins\\GizzlePlugins\\CheckoutPlugin:
    enabled: true
    repository: localpath
    branch: master
```
