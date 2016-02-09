# JSON Specification

All party data is JSON encoded (for ease of use). Each party object is located in it's own file (for ease of use).

## Basic concepts

### Party object

Party data is wrapped in a single object with the following properties:

**Party Code** 

Used as unique identifier.

Party code should follow the standard format:

National parties:

`PP<nationalISOCode>`

SubParties

`PP<nationalISOCode>-<subpartyCode>`

Note: The data format is mostly self explanitory, but documentation will be provided in time.

```
"partyCode": "PPSI"
```

**Country Code**

Used to define country of origin. Presumes only one party per country.

Identified with two char [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) code. 

```
"countryCode": "si"
```

**Country Name** 

Used to define country of origin. Full english name of country.
Why? Because lazy & better readability.

```
"country": "Slovenia",
```

**Party Name** 

Used to define name of the party in english and original language(s). Supports any number of translations (for multi-lingual countries), but english is required.

Key is two char [ISO language code](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes).
```
"partyName": {
        "en": "Pirate Party Slovenia",
        "sl": "Piratska stranka Slovenije"
    },
```

**Party Type** 

Used to differentiate between national, regional, local, and youth parties.

Allowed values:
* national
* regional
* local
* youth

This dataset should not be used to log international organisations. 

```
"type": "national",
```

**Region**

*For parties of _type_ REGIONAL only!*

Upper/Lower case, spaces etc. will be preserved. Use english names.

```
"region": "Bavaria",
```

**Parent organisation** 

Required if Party Type is other than "national". Value must be Party Code of parent organisation.

```
"parentorganisation": "PPSI",
```

**Headquarter**

Used to mark the location of the headquarters. Requires at least one subkey, or both.

Subkeys:

Coordiantes (optional) - with demical degrees.
Address (optional) - Full string of the address. Can include newlines with apropriate character.

```
"headquarters": {
    "coordinates": {
        "longitude": "14.505751",
        "latitude" : "46.056947 "
    },
    "address": "Majaronova ulica 6, 1000 Ljubljana"
}
```

**Websites** 

Must list the official website. Can list other *public* websites.

Known keys:
* official (required)
* forum
* liquidfeedback
* wiki

Other keys can also be added but will be ignored by the website untill implemented. If you add an unknown key submit a ticket.

*Don't forget to properly escape slashes!*

```
"websites": {
        "official": "http:\/\/www.piratskastranka.si\/"
    },
```

**Social networks** 

List of all social networks (and other *public* communication platforms) used by the party. 

Most platforms are already implemented and described below. Other platforms (keys) can also be added but will be ignored by the website untill implemented. If you add an unknown key(platform) submit a ticket.

Value type differs between social networks and MUST be uniform across the dataset.

*Facebook Page*

Facebook pages ONLY.

TEXT_ID - textual id (last part of the URL)
INTEGER_ID - (optional) 
```
"facebook": {
    "username": "TEXT_ID",
    "id": "INTEGER_ID"
},
```

*Twitter*

USERNAME - Twitter handle *without* @ tag.

Hashtag can be:
* null
* Array of values, e.g. ["#ppsi", "#piraten"] *with # signs*

```
"twitter": {
    "username": "USERNAME",
    "hashtag": ["#ppsi", "#piraten"]
},
```

*Google Plus*

ID - integer 

```
"googlePlus": "ID",
```
Full example:

```
"socialNetworks": {
        "facebook": {
            "username": "Piratska.Stranka.Slovenije",
            "id": "98358327274"
        },
        "twitter": {
            "username": "piratskastranka",
            "hashtag": null
        },
        "googlePlus": "115846887465357040331",
        "youtube": "PiratskaStranka",
        "irc": {
            "ircChannel": "#piratskastranka",
            "ircServer": "irc.freenode.net"
        }
    },
```

**Membership**

Used to indicate membership in *international* organisations.

Other organisations (keys) can also be added but will be ignored by the website untill implemented. If you add an unknown key(organisation) submit a ticket.

Currently supported:

* "ppi" - Pirate Parties International
* "ppeu" - European Pirate Party

Accepted keys are:
* full
* observer

Others can be added on request.

```
"membership": {
        "ppi": "full",
        "ppeu": "full"
    },
```

**Contact**

Used to log different *official* contact channels.

Any type of contact may be added, but "general" is required.

NOTE: keys must start with lowercase, and be foramted in cammelCase. For display "overseeCommittee" will be transformed into "Oversee Committee".

All will be displayed on the website.

For contact type "email" is the only one currently supported. Support for others (jabber, skype, ...) can be added if requested.

```
"contact": {
    "general": {
        "email": "info@piratskastranka.si"
    },
    "international": {
        "email": "international@piratskastranka.si"
    }
}
```
