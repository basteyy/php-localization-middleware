# LocalizationMiddleware

A PHP middleware for handling localization and language preferences in web applications.

## Installation

You can install this package via Composer. Run the following command:

```bash
composer require basteyy/php-localization-middleware
```

## Usage

To use this middleware in your PHP application, follow these steps:

1. Create an instance of the LocalizationMiddleware class with your desired configuration and add it as middleware. For example:
```php
    $app->add(new \basteyy\LocalizationMiddleware\LocalizationMiddleware(
        default_language: 'de',                     // Default language
        available_languages: ['de', 'ua', 'en'],    // Supported languages
        patch_requested_url: true,                  // Patch the requested url to remove the language in case there is one
        browser_overwrite_url: false,               // Overwrite the url language with the browser language
        patch_only_exactly_match: false             // Patch requested url only if the language is exactly matched (only when url contain a supported language)
    ));
```
The middleware will automatically determine the preferred language based on the request and set it as an attribute on the request object. You can access the language 
   attribute using `$request->getAttribute('language')`.

## Configuration
The LocalizationMiddleware class can be configured with the following options:

* `$default_language`: The default language to use when no preferred language is detected. It should be a two-letter language code (e.g., 'en').
* `$available_languages`: An array of supported languages, each represented by a two-letter language code (e.g., 'en', 'de', 'fr').
* `$patch_requested_url`: Set to true to patch the requested URL by removing the language prefix (e.g., '/en/...').
* `$browser_overwrite_url`: Set to true to overwrite the URL with the browser's preferred language even if the URL specifies a different language.
* `$patch_only_exactly_match`: Set to true, when only supported languages stripped from the url (path)

## License
ISC License

Copyright (c) 2023 basteyy <sebastian@xzit.online>

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted, provided that the above
copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
PERFORMANCE OF THIS SOFTWARE.
