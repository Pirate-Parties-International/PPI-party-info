# Pirate Party Info

[![Build Status](https://travis-ci.org/Pirate-Parties-International/PPI-party-info.svg?branch=master)](https://travis-ci.org/Pirate-Parties-International/PPI-party-info)

Central repository for basic information about Pirate Parties around the world.

The data is publicly accessible at http://api.piratetimes.net

Dataset also availabile via Composer:

    composer require pirates/papi-info

## How to use the information

### Raw files
You can access the raw json files in [data/](data/).

Organisation logos can be found in [logo/](logo/). 

### Compiled json file
All the json files can be compiled into a single file.

    php console info:compile

Note: this will output the data to stdout. You can create a file this way:

    php console info:compile > all_data.json

### Via a class

Install the package via composer. Than use the [Compile class](src/Compile.php).

## How to update/contribute information

Anyone can submit a request to change/update the data. This can be done in two ways:

### Pull request (prefered)
This is the fastes way. If you are familiar with git & github submit a pull request to this repository. One of the moderators will confirm the data validity of the request and merge the data. 

Documentation on data structure [can be found here](doc/data_specification.md)

You can verify data integrity by running:

    php console info:verify

### Submit an issue (for non-technical users)
Slower, but you may request that one of the administrators makes the change to the dataset for you. Please include the exact data and reasons for the change.

1. Go to [issues page](https://github.com/Pirate-Parties-International/PPI-party-info/issues) on this repository
2. Submit a new issue (Green button, "New issue")
3. Title should mention the party name
4. Add comment with exact data and reasons for change. Don't forget sources!
