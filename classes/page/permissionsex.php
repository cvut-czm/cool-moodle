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
 * PermissionsEssentials
 *
 * @package local_cool
 * @category page
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool\page;

defined('MOODLE_INTERNAL') || die();

use local_cool\entity\cohort;
use local_cool\entity\user;

class permissionsex {
    private $_context = null;

    public function __construct(\context $context) {
        $this->_context = $context;
    }

    public function any(bool ...$permissions) {

    }

    public function all(bool ...$permissions) {

    }

    /**
     * @return permissionsex
     * @throws access_denied_exception
     */
    public function require_logged(): permissionsex {
        if (!isloggedin()) {
            throw new access_denied_exception();
        }
        return $this;
    }

    /**
     * @return permissionsex
     * @throws access_denied_exception
     */
    public function require_cohort_member(cohort $cohort): permissionsex {
        if (!$cohort->is_member(user::current_user())) {
            throw new access_denied_exception();
        }
        return $this;
    }

    public function require_capability(string $capability) {
        if (!has_capability($capability, $this->_context)) {
            throw new access_denied_exception();
        }
        return $this;
    }
}