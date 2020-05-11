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
 * Admin setting for AWS regions.
 *
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2020 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace local_aws;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/adminlib.php');

/**
 * Admin setting for AWS regions.
 *
 * @copyright  2020 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_settings_aws_regions_select extends \admin_setting_configselect {
    /**
     * Constructor.
     *
     * @param string $name Setting  name name.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param string|int $defaultsetting
     */
    public function __construct($name, $visiblename, $description, $defaultsetting) {
        parent::__construct($name, $visiblename, $description, $defaultsetting, null);
    }

    /**
     * Load the available choices for the select box.
     *
     * @return bool
     */
    public function load_choices() {
        global $CFG;

        if (is_array($this->choices)) {
            return true;
        }

        $all = require($CFG->dirroot . '/local/aws/sdk/Aws/data/endpoints.json.php');
        $ends = $all['partitions'][0]['regions'];
        if ($ends) {
            foreach ($ends as $key => $value) {
                $this->choices[$key] = $key . " - " . $value['description'];
            }
        }

        return true;
    }
}
