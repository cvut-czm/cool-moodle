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
 * Abstract page
 *
 * @package local_cool
 * @category page
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\page;

defined('MOODLE_INTERNAL') || die();

abstract class abstract_page {

    private $permex = null;

    protected $title = null;
    protected $heading = null;
    protected $url = null;

    protected final function permissionsex(): permissionsex {
        if ($this->permex == null) {
            $this->permex = new permissionsex($this->context());
        }
        return $this->permex;
    }

    public final function require_param(string $name, string $type = PARAM_INT) {
        return required_param($name, $type);
    }

    public final function get_param(string $name, $default = null, string $type = PARAM_INT) {
        return optional_param($name, $default, $type);
    }

    public final function render_form(\moodleform $form) {

    }

    public final function render_page(string $page, $context = null) {
        global $PAGE;
        $PAGE->set_context($this->context());
        if ($this->title == null) {
            $PAGE->set_title('Moodle');
        } else {
            $PAGE->set_title($this->title);
        }
        if ($this->heading != null) {
            $PAGE->set_heading($this->heading);
        }
        $PAGE->set_url($this->url);

        /** @var \plugin_renderer_base $renderer */
        $renderer = $PAGE->get_renderer($this->component());

        print($renderer->header());
        print($renderer->render_from_template($this->component() . '/' . $page, $context));
        print($renderer->footer());
    }

    protected abstract function global_permission(permissionsex $perm);

    protected abstract function context(): \context;

    protected abstract function component(): string;

    protected abstract function run();

    public final static function execute() {
        $instance = new static();
        $instance->global_permission($instance->permissionsex());
        $instance->run();
    }
}