# Threader BOT
![Code Coverage Badge](./clover_badge.svg) 

ThreaderBot is a Twitter bot that responds to mentions with links by summarizing those links in a thread of tweets.

## Requirements

- PHP 7.4+
- composer

## Installation instructions

- clone this repo
- run composer install
- you can run composer run tests

## Configuration

Copy scripts/.env into scripts/.env.local and overwrite given values with your Twitter API access tokens (both v1 and v2 are needed).

## Execution

Run the script scripts/twitter_mentions.php at fixed intervals, it will look for new mentions and publish summarization threads in response.

## Whow it works

When a new Twitter mention with a link is received, the content is retrieved and parsed in three phases (see NachoBrito\TTBot\Article\Infraestructure\ChainTextExtractor):

- First, a readibility implementation is used to remove all ads and non relevant text from the html document.
- After that, an HTML2Text extractor is used to get clean text without html markup
- The final step is to use a TextRank summarizer to extract the most relevant sentences in text.

Once the summary is generated, a new collection of tweets is published as a thread in response to the original mention.

## Technical details

The project is heavily inspired by an hexagonal architecture, plus some commonly used patterns:

- Dependency Injection: Current implementation uses Symfony's container. See scripts/inc/services.php for runtime setup.
- Command Query Responsability Segregation: The application is accessed "from the outside" with either a Command or a Query. Current implementation uses Symfony's Messenger component for dispatching. See Common/Infraestructure/SymfonyCommandBus.php, Common/Infraestructure/SymfonyQueryBus.php

A rate limiting feature is also implemented, with Symfony's rate limiter component. Currently the token bucket algorithm is applied to prevent excesive user access.


## Contact

Feel free to post an issue to this repository for any question or comment :-)