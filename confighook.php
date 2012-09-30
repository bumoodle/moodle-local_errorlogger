<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Configuration hook. 
 * This file contains an (ugly) hack which allows database-driven logging from Moodle.
 *
 * @package    local_errorlogger
 * @copyright  2012 Binghamton University
 * @author     Kyle J. Temkin <ktemkin@binghamton.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Note the 'set_exception_handler' line at the end of this file, which overrides the exception handler
// in setup.php.

/**
 * Handles every uncaught exception that occurs within Moodle code.
 * 
 * @param exception $e The exception that was thrown.
 * @access public
 * @return void Does not return! Calls the default exception handler, which dies.
 *
 */
function logging_exception_handler($e) {

    global $DB, $USER, $CFG;

    try
    {

        // If we have a database handle, and 
        if(!during_initial_install() && !empty($DB)) {

            // Get any information provided by the given exception in a more workable format.
            $info = get_exception_info($e);

            // Populate the database fields from the known information and $info.
            $record = new stdClass;
            $record->time = time();             //Time the error occurred.
            $record->message = $info->message;
            $record->backtrace = format_backtrace($info->backtrace, true);
            $record->debuginfo = $info->debuginfo;
            $record->errorcode = $info->errorcode;

            // Create a reference hash, which is unique enough to allow easy access to the problem.
            // (Note that SHA1 and this setup are okay here, since we're not concerned about security
            //  or the likihood of collisions. 
            //
            //  The site's URL is included as a "salt" to prevent deduction of implementation tables
            //  via "rainbow" tables.)
            $record->reference = sha1($record->backtrace . $record->debuginfo . $CFG->wwwroot);

            // If moodlelib has already been loaded, use it to get
            // the current user's IP information.
            if(function_exists('getremoteaddr')) {
                $record->ip = getremoteaddr();      
            }

            // If we have a valid ID for the logged-in user, add it.
            if(!empty($USER) && !empty($USER->id)) {
                $record->userid = $USER->id;
            }

            // Store the record in the database.
            $id = $DB->insert_record('errors', $record);
        }

    }
    // Ignnore all exception which occur during the exception logging routine, as we can't log those.
    // Chances are, the same exception is going to generate the message below, which will end up in the PHP error log.
    catch(exception $e) { }

    
    //Call Moodle's default error handler, so the system can proceed.
    default_exception_handler($e);
}

// Instruct Moodle to use our logging exception handler, rather than the default one.
if(!PHPUNIT_TEST or PHPUNIT_UTIL) {
    set_exception_handler('logging_exception_handler');
}


