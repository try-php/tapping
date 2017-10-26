# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0] - 26.10.2017
### [Added]

* `test` function that forks off the test as new process
	...and renders a progress indicator, aswell as handles success or fail of the test. Since the test is a new process, it is necessary that the process exits like a "normal" process (exit(0) for success, exit(1) for fail).

* flag support for flag `--build` or `-b`, which exit the parent script with 1 so a ci build would exit

* flag support for flag `--quite` or `-q` which will suppress output of fail information

* added API docs

* `output` prediction

* `exception` prediction

* `is` prediction

* removed

* error handler function

### [Changed]

* output to stream write

* namespace to `Tapping`

