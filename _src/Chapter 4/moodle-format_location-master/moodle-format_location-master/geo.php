<?php
// This file is part of the Kamedia GPS course format for Moodle - http://moodle.org/
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
 * GPS Format free. Restrict access to topics according to user's geolocation.
 *
 * @package format_location
 * @copyright 2013 Barry Oosthuizen
 * @author Barry Oosthuizen
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define('AJAX_SCRIPT', true);

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_sesskey();
require_login();

$userlatitude = optional_param('latitude', null, PARAM_FLOAT);
$userlongitude = optional_param('longitude', null, PARAM_FLOAT);

$location = new stdClass();
$location->userid = $USER->id;
$location->latitude = $userlatitude;
$location->longitude = $userlongitude;
$location->timemodified = time();

global $DB;

if ($currentrecord = $DB->get_record('format_location_user', array("userid" => $USER->id))) {
    $location->id = $currentrecord->id;
    try {
        $DB->update_record('format_location_user', $location);
    } catch (dml_exception $e) {
        echo json_encode($e->debuginfo . ', ' . $e->getTraceAsString());
    }

} else {
    try {
        $DB->insert_record('format_location_user', $location);
    } catch (dml_exception $e) {
        echo json_encode($e->debuginfo. ', ' . $e->getTraceAsString());
    }
    
}
