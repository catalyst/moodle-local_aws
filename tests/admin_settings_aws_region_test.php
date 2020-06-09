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
 * local_aws unit tests.
 *
 * @package   local_aws
 * @author    Mikhail Golenkov <mikhailgolenkov@catalyst-au.net>
 * @copyright 2020 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_aws\tests;

defined('MOODLE_INTERNAL') || die();

use local_aws\admin_settings_aws_region;

/**
 * Testcase for the list of AWS regions admin setting.
 *
 * @package    local_aws
 * @author     Mikhail Golenkov <mikhailgolenkov@catalyst-au.net>
 * @copyright  2020 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_settings_aws_region_testcase extends \advanced_testcase {

    /**
     * Test that output_html() method works and returns HTML string with expected content.
     */
    public function test_output_html() {
        $setting = new admin_settings_aws_region('test_aws_region',
            'Test visible name', 'Test description', 'Test default setting');
        $html = $setting->output_html('');
        $this->assertContains('Test visible name', $html);
        $this->assertContains('Test description', $html);
        $this->assertContains('Default: Test default setting', $html);
        $this->assertContains('<input type="text" list="s__test_aws_region" name="s__test_aws_region" value=""', $html);
        $this->assertContains('<datalist id="s__test_aws_region">', $html);
        $this->assertContains('<option value="', $html);
    }
}
