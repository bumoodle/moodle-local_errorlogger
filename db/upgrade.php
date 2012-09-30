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
 * This file keeps track of upgrades to the Error Logger local "hack".
 *
 * Sometimes, changes between versions involve alterations to database structures
 * and other major things that may break installations.
 *
 * The upgrade function in this file will attempt to perform all the necessary
 * actions to upgrade your older installation to the current version.
 *
 * If there's something it cannot do itself, it will tell you what you need to do.
 *
 * The commands in here will all be database-neutral, using the methods of
 * database_manager class
 *
 * Please do not forget to use upgrade_set_timeout()
 * before any action that may take longer time to finish.
 *
 * @package local
 * @copyright 2012 Binghamton University
 * @author Kyle J. Temkin <ktemkin@binghamton.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * As of the implementation of this block and the general navigation code
 * in Moodle 2.0 the body of immediate upgrade work for this block and
 * settings is done in core upgrade {@see lib/db/upgrade.php}
 *
 * There were several reasons that they were put there and not here, both becuase
 * the process for the two blocks was very similar and because the upgrade process
 * was complex due to us wanting to remvoe the outmoded blocks that this
 * block was going to replace.
 *
 * @global moodle_database $DB
 * @param int $oldversion
 * @param object $block
 */
function xmldb_local_errorlogger_upgrade($oldversion) {
    global $DB;

    // Get the database manager object.
    $dbman = $DB->get_manager();

    // Moodle v2.2.0 release upgrade line
    // Put any upgrade step following this

    // Moodle v2.3.0 release upgrade line
    // Put any upgrade step following this
    

    //Automatically generated code
    if ($oldversion < 2012072801) {

        // Define field id to be added to errors
        $table = new xmldb_table('errors');
        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, null, null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);

        // Conditionally launch add field id
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // assignment savepoint reached
        upgrade_plugin_savepoint(true, 2012072801, 'local', 'errorlogger');
    }


    if ($oldversion < 2012072803) {

        // Define field reference to be added to errors
        $table = new xmldb_table('errors');
        $field = new xmldb_field('reference', XMLDB_TYPE_TEXT, null, null, null, null, null, 'errorcode');

        // Conditionally launch add field reference
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // assignment savepoint reached
        upgrade_plugin_savepoint(true, 2012072803, 'local', 'errorlogger');
    }





    return true;
}
