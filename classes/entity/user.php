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
 * User entity
 *
 * @package local_cool
 * @category entity
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\entity;

defined('MOODLE_INTERNAL') || die();

class user extends database_entity {
    const TABLENAME = 'user';

    protected $auth;
    protected $email;
    protected $confirmed;
    protected $policyagreed;
    protected $deleted;
    protected $firstname;
    protected $lastname;
    protected $username;
    protected $idnumber;

    public function get_email() : string {
        return $this->email;
    }

    public function get_username(): string {
        return $this->username;
    }

    public function get_fullname(): string {
        return $this->firstname . ' ' . $this->lastname;
    }

    private static $current = null;
    private static $currentset = false;

    public static function current_user(): self {
        global $USER;
        if (self::$currentset == false) {
            try {
                self::$current = self::get(['id' => $USER->id]);
            } catch (\dml_exception $e) {
                self::$current = null;
            }
            self::$currentset = true;
        }
        return self::$current;
    }

    public function set_idnumber(string $idnumber)
    {
        $this->idnumber=$idnumber;
    }
    public function set_firstname(string $firstname)
    {
        $this->firstname=$firstname;
    }
    public function set_lastname(string $lastname)
    {
        $this->lastname=$lastname;
    }
}