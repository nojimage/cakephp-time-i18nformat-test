# Test for CakePHP I18n/FrozenTime json serialization

This is a repository to indicate that the timezone part of JSON serialization
of `FrozenTime` is broken in the environment of `libICU < 51`.

## How to Run

use `docker-compose`:

```bash
docker-compose build
docker-compose run --rm icu50
docker-compose run --rm remi
```

### Docker images

- icu50: based CentOS 7, install PHP 7.2 from SCL => libICU: 50.2
- remi: based CentOS 7, install PHP 7.2 from remi => libICU: 62.1

## Results

```bash
$ docker-compose run --rm icu50
ICU version: 50.2
Default (timezone part=xxx): 2019-12-13T02:13:39
Alter (timezone part=ZZZZZ): 2019-12-13T02:13:39Z
Alter (format DATE_ATOM):    2019-12-13T02:13:39+00:00
```

```bash
docker-compose run --rm remi
ICU version: 62.1
Default (timezone part=xxx): 2019-12-13T02:14:04+00:00
Alter (timezone part=ZZZZZ): 2019-12-13T02:14:04Z
Alter (format DATE_ATOM):    2019-12-13T02:14:04+00:00
```

## Fixes proposal

### 1. Use `ZZZZZ` instead of `xxx`

The difference between `ZZZZZ` and `xxx` is that `'Z'` is returned instead of `'+00:00'` when in UTC timezone.
'Z' is also correctly interpreted by the JavaScript `Date` class.

Quick fix: Add the below code in your app's `config/bootstrap.php`,

```php
\Cake\I18n\FrozenTime::setJsonEncodeFormat("yyyy-MM-dd'T'HH':'mm':'ssZZZZZ");
\Cake\I18n\Time::setJsonEncodeFormat("yyyy-MM-dd'T'HH':'mm':'ssZZZZZ");
```

### 2. Add `$useI18nFormat` to `setJsonEncodeFormat`'s 2nd argument

Is i18n format required for json encoding?

`format` is faster than `i18nFormat`:

```bash
docker-compose run --rm remi php /data/performance.php
Number of loops: 1,000,000
use i18nFormat: 8.725sec
use format: 0.854sec
```

Add `$useI18nFormat` to `setJsonEncodeFormat`'s 2nd argument.
The param be used to control the formatting method.

```php
// `$useI18nFormat` to false, then jsonSerialize will use `format` method.
FrozenTime::setJsonEncodeFormat(DATE_ATOM, false);
// For backward compatibility, Default `$useI18nFormat` is `true`.
FrozenTime::setJsonEncodeFormat("yyyy-MM-dd'T'HH':'mm':'ssZZZZZ");
```
