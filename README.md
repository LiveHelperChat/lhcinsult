# Insult detection extensions

 * For detecting insulting messages we use. https://demo.deeppavlov.ai/#/en/insult AI to detect does visitor messages contains insulting message. 
 * For detecting nude uploaded images we use https://github.com/notAI-tech/NudeNet Rest API `docker run -it -p8080:8080 notaitech/nudenet:classifier`

# How it works?

## Messages
If insult is detected to visitor is written that his message is insulting. Operator has option to mark this message as not insulting. On third insulting message we terminate the chat.

## Images
If we detect nude images we remove file instantly and replace a visitor uploaded file with a simple message telling his uploaded image is inappropriate.

# Requirements

This extension requires
* PHP Resque extension running. https://github.com/LiveHelperChat/lhc-php-resque
* Deep pavlov API running.
* Live Helper Chat 3.39v just checkout from master branch.

# Install guide

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
    * Query attribute `x` or `q` once you install DeepPavlov.ai see api address.
    * `Attribute location` defines where `Insult`/`Not Insult` text is located. `:` is a separation for next level. `0:4` means take first element of array and then take 4's element of an array.


