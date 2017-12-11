# Changelog
All notable changes to this project will be documented in this file.

## [1.2.3] - 2017-07-18

### Changed
- replace `delahaye/dlh_geocode` with this package

## [1.1.2] - 2017-07-18

### Fixed
- contao 3 requires `contao-community-alliance/composer-plugin` in version `~2.4`

## [1.1.1] - 2017-07-18

### Added
- `tl_dlh_geocode` table for better geo code caching 

## [1.1.0] - 2017-07-18

### Fixed
- `contao 4.x` compatibility
 
### Changed

- make always usage of api key, global api key can now added to `tl_settings.dlh_googlemaps_apikey`, required for pageless context
