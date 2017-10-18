# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### [Added]

* `test` function that forks off the test as new process
	...and renders a progress indicator, aswell as handles success or fail of the test. Since the test is a new process, it is necessary that the process exits like a "normal" process (exit(0) for success, exit(1) for fail).