electric
========

A Symfony project created on November 5, 2017, 3:19 pm.


## How to run

You need node.js, yarn to compile frontend

Pre-launch tasks as per order:
---
`composer install`

Check db connection settings in `app/config/parameters.yml`

`yarn install`

`./node_modules/.bin/encore dev`

`bin/console doctrine:database:create`

`bin/console doctrine:schema:update --force`

Launch
---
`bin/console thruway:process start`

`bin/console server:start`

And navigate to uri, printed in console.
