<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');

/**
 * Block adastra_setup is defined here.
 *
 * @package     block_adastra_setup
 * @copyright   2020 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * adastra_setup block.
 *
 * @package    block_adastra_setup
 * @copyright  2020 Your Name <you@example.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_adastra_setup extends block_list {

    const STR_PLUGINNAME = 'block_adastra_setup';
    const PLUGINNAME = 'adastra_setup';

    /**
     * Return the path to the mod adastra directory.
     *
     * @return string The path.
     */
    public static function get_mod_adastra_path() {
        // PHP class constants cannot be defined with a return value from a function call,
        // so we have this static method.
        global $CFG;
        return $CFG->dirroot.'/mod/adastra';
    }

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', self::STR_PLUGINNAME);
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         = new stdClass();
        $this->content->items  = array();
        $this->content->icons  = array();
        $this->content->footer = '';
        $cid = $this->page->course->id; // Course id.
        $context = context_course::instance($cid);

        if (has_capability('mod/adastra:addinstance', $context)) {
            /* Links to course-level Ad-Astra exercise administration:
             * 1) edit exercises.
             */

            // Edit exercises.
            $this->content->items[] = $this->render_list_item(\mod_adastra\local\urls\urls::edit_course($cid, true),
                    'editexercises', 'i/settings');
        }

        return $this->content;
    }

    protected function render_list_item(moodle_url $linkurl, $linktextid, $iconname) {
        global $OUTPUT;

        $text = get_string($linktextid, self::STR_PLUGINNAME);
        $icon = $OUTPUT->pix_icon($iconname, $text);
        /* pix_icon produces the HTML for the icon <img> element, taking into account that
         * themes may override core icons. Modern themes may output Font Awesome icons
         * in Moodle versions 3.3+
         */

        return html_writer::link($linkurl, $icon . ' ' . $text);
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return array(
            'course-view' => true,
            'mod_adastra' => true,
        );
    }

    /**
     * Return an array of capabilities that are used by the block plugin
     * (other than defined in db/access.php).
     *
     * @return string[] Array of capabilities.
     */
    public static function get_extra_capabilities() {
        $caps = parent::get_extra_capabilities();
        $caps[] = 'mod/adastra:addinstance';
        return $caps;
    }

}
