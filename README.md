ICS
===

Class for iCalendar(.ics) file creation.

Is made into Laravel 4 environment as a library, but you can use it everywhere, always adding dependecies.

Dependency:

    Illuminate\Filesystem\Filesystem
		
Usage
=====

__Create the object:__

```php
$ics = new ICS('//Company//Product//EN', array(
	'startDate'   => $startTimestamp,
	'endDate'     => $endTimestamp,
	'address'     => $addressString,
	'summary'     => $summaryString,
	'uri'         => $uriString,
	'description' => $descriptionString
));
```
If you want you can contruct as required like:

```php
$ics = new ICS('//Company//Product//EN');

$ics->startDate($startTimestamp)
    ->endDate($endTimestamp)
    ->address($addressString)
    ->summary($summaryString)
    ->uri($uriString)
    ->description($descriptionString);
```

__Get the markup as string:__

```php
$ics->get();
```

__Save file to file:__

_If there is no file name this will be date('Ymd', time())_.

```php
$ics->save('path/to/save', 'optional_file_name');
```

Also you can construct as required like:

```php
$ics->path = 'path/to/save';
$ics->save();
```

You can get the file path from:

```php
$ics->path;
```

__Delete file:__

```php
$ics->delete();
```

Enjoy!