# ElasticPress Embargo Attachments

A WordPress plugin that allows media library files to be embargoed from search when using [ElasticPress](https://github.com/10up/ElasticPress).

By default, ElasticPress will return all media library items in search results if Document Search is activated. Items are indexed on upload, and immediately start being returned in search.

This plugin provides an "Embargo datetime" option on media library items, and then hides those items from search until the datetime has passed.

## Installation 

Clone this repo into your WordPress `/plugins/` directory, and activate the plugin.

You will need to re-index the first time you set an embargo datetime. After that, ElasticPress will recognise embargo datetimes automatically.

## Development

Install the dependencies:

```
composer install
```

Run the tests:

```
vendor/bin/peridot spec
```

Run the linter:

```
vendor/bin/php-cs-fixer fix 
```
