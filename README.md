Database Error Reporter Hack for Moodle.
==================

**Caution- work has not been thoroughly tested!**

Description
------------------

This local plugin adds a special exception handler, which allows exceptions to be logged to the Moodle database, when possible. This is intended to supplement (and not replace) the built-in logging mechanism.

The special exception handler is installed as a "hook", which safely calls the database handler when possible. There _should_ be no negative effects if the database insert fails, but this has not been logically proven.

In conjunction with the Error Log Report, this plugin _should_ allow instructors to view limited exception logs relevant to students in their courses. This will hopefully allow instructors to verify the veracity of claims that "Moodle crashed during a quiz!" or "I couldn't get the homework; Moodle said 'File not Found'!".

Installation:
------------------

To install on Moodle 2.0+ using git, execute the following commands in the root of your Moodle install:

    git clone git://github.com/ktemkin/moodle-local_errorlogger.git local/errorlogger
    echo '/local/errorlogger' >> .git/info/exclude

Or, extract the following zip in your_moodle_root/local/errorlog:

    https://github.com/ktemkin/moodle-local_errorlogger/zipball/master

Log in as an administrator, and allow the database update to commence. **After** the database update is complete, add the following line to the _end_ of your config.php, after setup.php is included:

    
    require_once(dirname(__FILE__) . '/local/errorlogger/confighook.php');
