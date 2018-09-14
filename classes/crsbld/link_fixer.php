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
 * This is a one-line short description of the file.
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    xxxxxx
 * @category   xxxxxx
 * @copyright  2018 CVUT CZM, Jiri Fryc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\crsbld;

defined('MOODLE_INTERNAL') || die();

class link_fixer {

    private $context;
    private $links = [];
    private $redone = [];

    public function __construct(\context_course $context) {
        $this->context = $context;
    }

    public function add_link(string $old_link) {
        if (!isset($this->links[$old_link])) {
            $this->links[$old_link] = $old_link;
        }
    }

    public function add_redone(string $table, string $id, array $fields) {
        $this->redone[] = [$table, $id, $fields];
    }

    public function redone() {
        global $DB;
        foreach ($this->redone as $redone) {
            $entity = $DB->get_record($redone[0], ['id' => $redone[1]]);
            foreach ($redone[2] as $field) {
                foreach ($this->links as $old => $new) {
                    $c=0;
                    $entity->{$field} = str_replace("\"".$old."\"", "\"".$new."\"", $entity->{$field},$c);
                }
            }
            $DB->update_record($redone[0], $entity);
        }
    }

    public function add_resource(string $old_link, string $new_link) {
        $this->links[$old_link] = $new_link;
    }

    public function read_image_to_base64(\stored_file $file) : string {
        return 'data:image/' . pathinfo($file->get_filename(), PATHINFO_EXTENSION) . ';base64,' .
                base64_encode($file->get_content());
    }
}