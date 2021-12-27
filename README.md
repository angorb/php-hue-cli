# php-hue-cli
A command line interface for interacting with Phillips Hue LED lights via PHP.

### Why?
I wanted an easy way to set up cron jobs on my Raspberry Pi home automation server that used the tools that I already had installed. This is very much a quick hack job to satisfy a specific need.

### Setup
Clone the repo. `./setup.sh` should take care of installing Composer dependencies and getting box set up for building a PHAR on PHP 8. It's not pretty.

Set your Hue hub IP address and auth token in `config/.env`. If you need get a new auth token, use the helper script at `vendor/bin/phue-create-user` will take care of that.

`vendor/bin/box compile` will build a standalone (well, composer dependencies included at least) phar at `bin/hue-cli` that can be copied into your $PATH somewhere.

### Usage

```
# List all lights associated with hub
$ hue-cli list

# Get a light or light(s) info
$ hue-cli info -t <light_id> (optional)

# set light #2's brightness to 100%
$ hue-cli brightness -t 2 -v 254

# changes light #5 to hex color #efa6d4
$ hue-cli rgb -t 5 -v efa6d4

# changes light #1 to cool white (color temp mode)
$ hue-cli colortemp -t 1 -v 153

... etc ...

```