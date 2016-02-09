# Pirate Party Info

Central repository for basic information about Pirate Parties around the world.

The data is publicly accessible at http://api.piratetimes.net


## How to update the information

Anyone can submit a request to change/update the data. This can be done in two ways:

1. *Pull request.* 
This is the fastes way. If you are familiar with git & github submit a pull request to this repository. One of the moderators will confirm the data validity of the request and merge the data. 
2. *Email.*
Slower, but you may email one of the moderators and request a data change. Please include the exact data and reasons for the change.

Documentation on data structure [can be found here](doc/data_specification.md)

## Structure

### Party meta data

Data about each party is located in the data/ directory. Each party has it's own file. 

`<partyCode>.json`

Party code should follow the standard format:

National parties:

`PP<nationalISOCode>`

SubParties

`PP<nationalISOCode>-<subpartyCode>`

Note: The data format is mostly self explanitory, but documentation will be provided in time.

### Party logo

Each logo should only be the circular logo, with transparent background.

### Country flag

See readme in the country-flag/ folder
