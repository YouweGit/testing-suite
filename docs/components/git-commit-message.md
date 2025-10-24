# Git Commit Message

The default configuration will validate the commit message adheres to the [Conventional Commit Message structure](https://cheatography.com/albelop/cheat-sheets/conventional-commits/)
and that the commit message body contains a Jira ticket number it relates to.

## Available configuration

The following configuration can be changed in your project's `grumphp.yml`:

```yaml
parameters:
    git_commit_message.type_scope_conventions:
        # Change list of allowed types, use `git_commit_message.type_scope_conventions: {}` to disable this feature
        types:
            - build
            - chore
            - feat
    git_commit_message.enforce_capitalized_subject: false
    git_commit_message.max_body_width: 72 # To disable this setting, use a value of 0
    git_commit_message.max_subject_width: 72
    # Configure your Jira Project codes as pipe-separated value
    git_commit_message.jira_projects: 'JIRAPROJ|OTHERPROJ'
    # Override if you want to change the Jira matcher, use `git_commit_message.jira_matcher: '/.*/` to disable it completely 
    git_commit_message.jira_matcher: '/\n.+ (%git_commit_message.jira_projects%)-\d+/'
    # Configure custom matchers, make sure to include the Jira matcher if you want to add more custom matchers
    git_commit_message.matchers:
        'Must contain JIRA issue number within the message body (e.g. "Resolves PROJECT-1234")': '%git_commit_message.jira_matcher%'
    git_commit_message.case_insensitive: false

```
