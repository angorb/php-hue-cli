#!/bin/bash
vendor/bin/box compile &&\
echo "Installing hue-cli to /usr/local/bin..." &&\
sudo cp bin/hue-cli /usr/local/bin/
