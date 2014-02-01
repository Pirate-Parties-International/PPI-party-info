# PPI Party Info

Central repository for basic information about Pirate Parties around the world.

The data is publicly accessible at http://api.piratetimes.net


## How update the information

To update the information submit a pull request to this repository. One of the moderators will confirm the request and merge the data.


## Structure

# Party meta data

Data about each party is located in the data/ directory. Each party has it's own file. 

`<partyCode>.json`

Party code should follow the standard format:

National parties:

`PP<nationalISOCode>`

SubParties

`PP<nationalISOCode>-<subpartyCode>`

# Party logo

Each logo should only the circular logo, with transparent background.

# Country flag

See readme in the country-flag/ folder