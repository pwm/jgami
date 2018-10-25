# JGami

[![Build Status](https://travis-ci.org/pwm/jgami.svg?branch=master)](https://travis-ci.org/pwm/jgami)
[![codecov](https://codecov.io/gh/pwm/jgami/branch/master/graph/badge.svg)](https://codecov.io/gh/pwm/jgami)
[![Test Coverage](https://api.codeclimate.com/v1/badges/94f5bb5073dc902b547f/test_coverage)](https://codeclimate.com/github/pwm/jgami/test_coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Have you ever mapped a function over a list? Now you can do the same with JSON!

JGami provides a simple API to update values in arbitrarily complex JSON structures while preserving structural integrity.

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

It's probably easiest to dive straight into some sample code. We will update some JSON data to our liking by mapping a function over it:

```php
// What we have
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
            "name": ["Woof"],
            "type": "Dog"
        },
        {
            "name": ["Mr. Grumpy", "Mrs. Grumpy"],
            "type": "Cat"
        }
    ]
}';

// What we want
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
            "name": ["Woof <3"],
            "type": "Dog"
        },
        {
            "name": ["Mr. Grumpy <3", "Mrs. Grumpy <3"],
            "type": "Cat"
        }
    ]
}';

// Our update function, to be mapped over our JSON data
$f = function (JsonNode $node): JsonNode {
    // Replace "Graphs" with "Trees"
    if ($node->val() === 'Graphs') {
        return StringNode::from($node, 'Trees');
    }
    // Add 10 to values with key "age"
    if ($node->key()->eq('age')) {
        return IntNode::from($node, $node->val() + 10);
    }
    // Add " <3" to values in paths with "pets" and "name"
    if ($node->path()->hasAll('pets', 'name')) {
        return StringNode::from($node, $node->val() . ' <3');
    }
    // Add " Wonderland" to values with key "name" that has no "pets" in their path
    if ($node->path()->hasNone('pets') && $node->key()->eq('name')) {
        return StringNode::from($node, $node->val() . ' Wonderland');
    }
    return $node;
};

// true
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
