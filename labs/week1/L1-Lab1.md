# REST Requests and Responses LAB

Goals:
1. Gain an understanding of REST requests and responses
2. Experience different REST verbs
3. Become familiar with JSON formatted data

## Requirements

* Git
* Node + NPM
* JSON Server
* CURL

## Setup

__JSON Server__

Open a terminal to this project directory

Install JSON Server globally

```
npm install -g json-server
```

To start JSON server run:

```
json-server --watch peoplePlacesThings.json
```

## Perform a GET request to each of the URLs

```
curl -v http://localhost:3000/people
curl -v http://localhost:3000/places
curl -v http://localhost:3000/things
```

Note the response body of each of the requests, and the request and response headers.

## Perform a GET request to fetch one specific person with id 1

```curl -v http://localhost:3000/people/1```

Note the response body of a single person, Morton Agge

## Perform a GET request to fetch person with id 99

```curl -v http://localhost:3000/people/99```

What was the response code?

## Perform a POST request to add a new person

```
curl -v -X POST \
  http://localhost:3000/people \
  -H 'Content-Type: application/json' \
  -d '{
  "first_name": "Test",
  "last_name": "McTest",
  "email": "test@fake.com"
}'
```

What was the response code?
Note the new id in the response body, request the person you just created.

## Perform a PUT request to replace the person with the new data

```
curl -v -X PUT \
  http://localhost:3000/people/1 \
  -H 'Content-Type: application/json' \
  -d '{
   id: 1,
  "ip_address": "1.2.3.4"
}'
```

What was the response code?
Note the response body. What happened to the data?

## Perform a PATCH request to update the person with the new data

```
curl -v -X PATCH \
  http://localhost:3000/people/2 \
  -H 'Content-Type: application/json' \
  -d '{
   "id": 1,
  "ip_address": "1.2.3.4"
}'
```

What was the response code?
Note the response body. What happened to the data this time?

## Perform a DELETE request delete the record of person with id 2

```
curl -v -X DELETE http://localhost:3000/people/2
```

What was the response code?
What was the response body?

## Perform another DELETE request with person id 2

```
curl -v -X DELETE http://localhost:3000/people/2
```

What was the response code?
