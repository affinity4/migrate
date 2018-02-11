---
title: Migrate Config
position: 3
parameters:
  - name:
    content:
content_markdown: |-
  You can customize your installation of Migrate by creating migrate.json in the root of your project.

  All paths are relative from the directory where this file is.

  Migrate must be run from the directory where this file is located.
left_code_blocks:
  - code_block: |-

    title:
    language:
right_code_blocks:
  - code_block:
    {
      "migration_dirs": "migrations/dev"
    }
    title: migrate.json
    language: json
---