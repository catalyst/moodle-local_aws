#!/bin/bash

rm -rf sdk
wget -O sdk.zip "http://docs.aws.amazon.com/aws-sdk-php/v3/download/aws.zip"
unzip sdk.zip -d sdk
sed -i -e 's/require/require_once/g' sdk/aws-autoloader.php
sed -i -e 's#GuzzleHttp/functions.php#GuzzleHttp/functions_include.php#g' sdk/aws-autoloader.php
sed -i -e 's#GuzzleHttp/Psr7/functions.php#GuzzleHttp/Psr7/functions_include.php#g' sdk/aws-autoloader.php
sed -i -e 's#GuzzleHttp/Promise/functions.php#GuzzleHttp/Promise/functions_include.php#g' sdk/aws-autoloader.php
rm sdk.zip
echo
echo 'Go update version.php'