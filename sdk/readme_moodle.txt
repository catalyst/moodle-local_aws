#!/bin/bash
# Run this script from the sdk folder
# Update version.php verion and release
# Manually test that the SDK can be used with no issues.

SDKDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"


find $SDKDIR -mindepth 1 -maxdepth 1 | grep -v readme_moodle.txt | xargs rm -rf
wget -O $SDKDIR/sdk.zip "http://docs.aws.amazon.com/aws-sdk-php/v3/download/aws.zip"
unzip $SDKDIR/sdk.zip -d $SDKDIR
sed -i -e 's/require/require_once/g' $SDKDIR/aws-autoloader.php
sed -i -e 's#GuzzleHttp/functions.php#GuzzleHttp/functions_include.php#g' $SDKDIR/aws-autoloader.php
sed -i -e 's#GuzzleHttp/Psr7/functions.php#GuzzleHttp/Psr7/functions_include.php#g' $SDKDIR/aws-autoloader.php
sed -i -e 's#GuzzleHttp/Promise/functions.php#GuzzleHttp/Promise/functions_include.php#g' $SDKDIR/aws-autoloader.php
rm $SDKDIR/sdk.zip
echo
echo 'Go update version.php'
echo $SDKDIR

