# Change Log
All notable changes to this project will be documented in this file.

## v2.3.2 - UNRELEASED
Nothing yet.

## v2.3.1 - 2018-10-16
### Fixed
- Unstrict phalcon version

## v2.3.0 - 2018-10-15
### Changed
- Use latest phalcon 3.4

## v2.2.1 - 2016-10-18
### Fixed
- Exclude test directory for export by git

## v2.2.0 - 2016-10-02
### Fixed
- Ensure DI response object contains returned value of dispatched action
### Added
- Make application injectable for DI's `applicationEventManager`
- Support options (arguments with `--`, `-`, `=`) for cli application
### Changed
- Internal refactoring (renamings) with internal BCs

## v2.1.0 - 2016-09-26
### Added
- New parameter `position` to router configs

## v2.0.0 - 2016-09-13
### Changed
- Phalcon3 and PHP7 migration

## v1.0.4 - 2016-09-13
### Added
- Tests for forwarding controller action

## v1.0.3 - 2016-09-13
### Added
- Tests for use cases about having subfoldered controllers

## v1.0.2 - 2016-09-12
### Fixed
- Phalcon dependency constraint inside composer.json

## v1.0.1 - 2016-09-11
### Added
- Travis configuration in order to meet CI
- Makefile to wrap docker commands
- Dockerfile for containerized testing
- Functional tests for all use cases
### Fixed
- Successful routing with trailing slashes

## v1.0.0 - 2016-06-06
### Changed
- Remove Config merge feature, since Docker is comming into play

## v0.1.9 - 2016-04-11
### Fixed
- [#1: GLOB_BRACE breaks non-GNU systems](https://github.com/mamuz/phalcon-application/issues/1)

## v0.1.8 - 2016-02-16
### Fixed
- After merging Config the Phalcon Framework should not reindex the result

## v0.1.7 - 2016-02-03
### Changed
- Bump Copyright to 2016

## v0.1.6 - 2015-08-26
### Changed
- Make view directory configuration optionally

## v0.1.5 - 2015-08-18
### Changed
- DispatchListener for set RenderLevel in case of xhr request

## v0.1.4 - 2015-08-17
### Changed
- Remove Dispatch beforeExceptionListener

## v0.1.3 - 2015-08-17
### Changed
- DispatchListener for errorhandling and view return value

## v0.1.2 - 2015-08-17
### Fixed
- DispatchListener for getting request object

## v0.1.1 - 2015-08-16
### Added
- Project description composer

## v0.1.0 - 2015-08-16
### Added
- Add Factories
- Add Listeners
- Add Services
- Add Bootstrap
- Add DI
- Create skeleton
