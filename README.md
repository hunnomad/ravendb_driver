# RavenDB PHP Class

## Overview

This class provides a simple interface for interacting with a RavenDB database using HTTP requests. It supports basic operations such as `PUT`, `GET`, `QUERY`, and `DELETE` for managing documents within a RavenDB instance. The class utilizes cURL for making HTTP requests and expects a PEM file for SSL certificate verification.

## Features

- **PUT**: Store or update a document in the database.
- **GET**: Retrieve a document from the database by its ID.
- **QUERY**: Perform a custom query against the database.
- **DELETE**: Remove a document from the database.

## Requirements

- PHP 7.4 or higher
- cURL extension enabled
- A valid RavenDB server setup with SSL certificates

## Installation

To use this class, simply include it in your PHP project:

```php
require_once 'RavenDB.php';
```
Then instantiate it with the required parameters:
```php
$ravenDB = new RavenDB('https://your-server-url', 'your-database-name', '/path/to/ssl/certificate.pem');
```
#### USAGE

**1. Put a Document**

```php
$doc = [
    "Name" => "John Doe",
    "Email" => "john.doe@example.com"
];

$id = "users/1";

$ravenDB->put($id, $doc);
```
**2. Get a Document**

```php
$id = "users/1";
$document = $ravenDB->get($id);
print_r($document);
```
**3. Query the Database**

```php
$query = "from Users where Name = 'John Doe'";
$results = $ravenDB->query($query);
print_r($results);
```
**4. Delete a Document**

```php
$id = "users/1";
$ravenDB->del($id);
```
**Error Handling**

This class throws exceptions when unexpected HTTP status codes are encountered. Make sure to handle errors properly.

```php
try {
    $ravenDB->put($id, $doc);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```
**License**

This project is licensed under the MIT License - see the LICENSE file for details.
