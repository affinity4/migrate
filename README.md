# Affinity4 Migrate

Create DB Migrations written entirely in JSON

## Installation

1. Add github repository to composer file

```

"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/affinity4/migrate"
    }
]

```

2. Add package to require-dev

```

"affinity4/migrate": "dev-master"

```

3. Run update/install from terminal

```

composer install

```

## Usage

### Create Table [tablename]

``` 

migrate create create-table::tablename

```
