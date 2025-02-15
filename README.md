# Insult detection extensions

 * For detecting insulting messages we use. https://demo.deeppavlov.ai/#/en/insult AI to detect does visitor messages contains insulting message. 
 * For detecting nude uploaded images we use https://github.com/notAI-tech/NudeNet Rest API `docker run -it -p8080:8080 notaitech/nudenet:classifier`
 * There is `docker-compose.yml` file prepared to run these services easily. Read Installation section.

# How it works?

## Messages
If insult is detected to visitor is written that his message is insulting. Operator has option to mark this message as not insulting. On third insulting message we terminate the chat.

## Images
If we detect nude images we remove file instantly and replace a visitor uploaded file with a simple message telling his uploaded image is inappropriate.

# Requirements

This extension requires
* PHP Resque extension running. https://github.com/LiveHelperChat/lhc-php-resque
* Deep pavlov API running or just `cd doc/docker/docker-compose` or just for detoxify
```
 docker run --pull always --rm --name lhc-detoxify \
  -p 8080:80 \
  remdex/lhc-detoxify:latest
```
* Live Helper Chat 3.39v just checkout from master branch.


# Install guide

* Execute SQL https://github.com/LiveHelperChat/lhcinsult/blob/master/doc/install.sql
* Run docker from `extension/lhcinsult/doc/docker` directory. First time starting service can take some time.
  * `docker-compose -f docker-compose.yml up` - to see how it starts
  * `docker-compose -f docker-compose.yml up -d` - to run as a service
* Clone repository to Live Helper Chat `extension` folder as `lhcinsult`
* Modify PHP Resque extension - `extension/lhcphpresque/settings/settings.ini.php` and add to `queues` `lhc_insult` item. You will see jobs related to insult detection.
* Execute doc/install.sql for extension.
* Activate extensions in Live Helper Chat settings file. `settings/settings.ini.php` by adding to `extensions` array `lhcinsult` extensions.
```
'extensions' => 
      array (
          'lhcinsult',
          'lhcphpresque',
          // 'nodejshelper' Optional if you have NodeJS extension running.
      ),
```
* In left menu under `Modules` you will find `Insult detection` extension. Go to options and configure it.
    * host have to be like `http://localhost:5000/model`
    * Query attribute `x` once you install DeepPavlov.ai see api address.
    * `Attribute location` defines where `Insult`/`Not Insult` text is located. `:` is a separation for next level. `0:0` means take first element of array and then take 0's element of an array.

## How to monitor status automatically?

This required if you are having a lot of messages and DeepPavlov messages check queue jobs queue is not getting close to zero.

This command monitors services health and disables them if required. This command should be run through cronjob every minute.

```shell
/usr/bin/php cron.php -s site_admin -e lhcinsult -c cron/check_health
```

## How to monitor service status and restart on failure

Over time I have noticed that time from time docker image just hangs up. And image is not restarted by docker service. Here is a small shell script which monitors status and restarts if required. This script should be run every 5 minutes or so.

```shell
#!/bin/bash

fileCron='/data/lhc-chatbot/script/image-dead'
imageTest='/data/lhc-chatbot/script/logo.png'

test=$( base64 -w 0 $imageTest )
data="{\"data\":{\"logo.png\":\"$test\"},\"webhook\":null}"
RESPONSE=$(curl -X POST -d "$data" -H 'Accept: application/json' -H 'Content-Type: application/json' --max-time 180 -s http://localhost:8080/sync)

if [[ $RESPONSE != *"unsafe"* ]]; then
   if [ ! -f $fileCron ];
    then
      echo "Creating lock file"
      touch $fileCron
    else
      /usr/bin/docker restart lhcinsul-image
      echo "Lock found. Restarting API"
    fi
else
  if [ -f $fileCron ]; then
    rm -f $fileCron
  fi
  echo "Live API"
fi
```

Cronjob command
```
*/5 * * * * /data/lhc-chatbot/script/monitor-image-insult.sh > /data/lhc-chatbot/script/log_insult.txt /dev/null 2>&1
```

