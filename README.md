# LocalizationMiddleware

A PHP middleware for handling localization and language preferences in web applications.

## Installation

You can install this package via Composer. Run the following command:

```bash
composer require basteyy/langugage-middleware
```

## Usage

To use this middleware in your PHP application, follow these steps:

1. Import the `LocalizationMiddleware` class:

```php
use basteyy\LocalizationMiddleware\LocalizationMiddleware;
```

2. Create an instance of the LocalizationMiddleware class with your desired configuration. For example:
```php
$middleware = new LocalizationMiddleware(
    'en',             // Default language
    ['en', 'de', 'fr'], // Supported languages
    true,             // Patch requested URL
    false             // Browser overwrite URL
);
```

3. Add the middleware to your application's middleware stack. The specific method for adding middleware may vary depending on your framework or application setup.
4. The middleware will automatically determine the preferred language based on the request and set it as an attribute on the request object. You can access the language 
   attribute using `$request->getAttribute('language')`.

## Configuration
The LocalizationMiddleware class can be configured with the following options:

* `$default_language`: The default language to use when no preferred language is detected. It should be a two-letter language code (e.g., 'en').
* `$available_languages`: An array of supported languages, each represented by a two-letter language code (e.g., 'en', 'de', 'fr').
* `$patch_requested_url`: Set to true to patch the requested URL by removing the language prefix (e.g., '/en/...').
* `$browser_overwrite_url`: Set to true to overwrite the URL with the browser's preferred language even if the URL specifies a different language.

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
