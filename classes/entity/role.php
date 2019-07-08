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
 * Role entity
 *
 * @package local_cool
 * @category entity
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\entity;

defined('MOODLE_INTERNAL') || die();

class role extends database_entity {
    const TABLENAME = 'role';

    protected $name;
    protected $shortname;
    protected $description;
    protected $sortorder;
    protected $archetype;

    public function get_shortname(): string {
        return $this->shortname;
    }

    public static function manager(): role {
        return self::get(['shortname' => 'manager']);
    }

    public static function coursecreator(): role {
        return self::get(['shortname' => 'coursecreator']);
    }

    public static function editingteacher(): role {
        return self::get(['shortname' => 'editingteacher']);
    }

    public static function teacher(): role {
        return self::get(['shortname' => 'teacher']);
    }

    public static function student(): role {
        return self::get(['shortname' => 'student']);
    }

    public static function prohibit(): ?role {
        return self::get(['shortname' => 'prohibit']);
    }

    public static function guest(): role {
        return self::get(['shortname' => 'guest']);
    }

    public static function user(): role {
        return self::get(['shortname' => 'user']);
    }

    public static function frontpage(): role {
        return self::get(['shortname' => 'frontpage']);
    }
}