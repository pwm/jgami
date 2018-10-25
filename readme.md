# JGami

[![Build Status](https://travis-ci.org/pwm/jgami.svg?branch=master)](https://travis-ci.org/pwm/jgami)
[![codecov](https://codecov.io/gh/pwm/jgami/branch/master/graph/badge.svg)](https://codecov.io/gh/pwm/jgami)
[![Test Coverage](https://api.codeclimate.com/v1/badges/94f5bb5073dc902b547f/test_coverage)](https://codeclimate.com/github/pwm/jgami/test_coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Have you ever mapped a function over a list? Now you can do the same with JSON!

JGami gives a handy way to update values in arbitrarily complex JSON structures while preserving its structural integrity.

It also provides a simple API for targeting parts of a JSON, allowing eg. to update only values under a specified path.

## Table of Contents

* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
* [How it works](#how-it-works)
* [Tests](#tests)
* [Changelog](#changelog)
* [Licence](#licence)

## Requirements

PHP 7.1+

## Installation

    $ composer require pwm/jgami

## Usage

```php
$json = '{
    "name": "Alice",
    "age": 27,
    "likes": [
        "Types",
        "Graphs",
        "Nature"
    ],
    "job": {
        "title": "Developer",
        "company": "Acme Corp."
    },
    "pets": [
        {
            "name": "Woof",
            "type": "Dog"
        },
        {
            "name": "Mr. Grumpy",
            "type": "Cat"
        }
    ]
}';

$expectedJson = '{
    "name": "Alice Wonderland",
    "age": 37,
    "likes": [
        "Types",
        "Trees",
        "Nature"
    ],
    "job": {
        "title": "Developer",
        "company": "Acme Corp."
    },
    "pets": [
        {
            "name": "Woof <3",
            "type": "Dog"
        },
        {
            "name": "Mr. Grumpy <3",
            "type": "Cat"
        }
    ]
}';

$f = function (JsonNode $node): JsonNode {
    if ($node->val() === 'Graphs') {
        return StringNode::from($node, 'Trees');
    }
    if ($node->key()->eq('age')) {
        return IntNode::from($node, 37);
    }
    if ($node->path()->hasAll('pets', 'name')) {
        return StringNode::from($node, sprintf('%s <3', $node->val()));
    }
    if ($node->path()->hasNone('pets') && $node->key()->eq('name')) {
        return StringNode::from($node, 'Alice Wonderland');
    }
    return $node;
};

assert(
    json_encode(json_decode($expectedJson))
    ===
    json_encode(JGami::map($f, json_decode($json)))
);
```
 
## How it works

TBD

## Tests

	$ composer utest
	$ composer phpcs
	$ composer phpstan
	$ composer infection

## Changelog

[Click here](changelog.md)

## Licence

[MIT](LICENSE)
