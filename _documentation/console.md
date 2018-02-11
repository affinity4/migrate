---
title: Authentication
position: 2
parameters:
  - name:
    content:
content_markdown: |-
  #### Migrate Console

  The format for the Migrate Console commands is `action:command table-action:action-modifier::table`

  ##### Create New Migration

  To create a new migration you will use the `migrate create:migration` command

  This will create a migration file in the "migrations" directory in the root of your project, or the directory specified in the migrate.json file.

left_code_blocks:
  - code_block:
    title:
    language:
right_code_blocks:
  - code_block: |-
      vendor/bin/migrate create:migration create-table::users
    title: create:migration create-table:users
    language: bash
---