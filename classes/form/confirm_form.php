<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\form;

global $CFG;
require_once("$CFG->libdir/formslib.php");

class confirm_form extends \moodleform
{

    /**
     * Form definition. Abstract method - always override!
     */
    protected function definition()
    {
        $mform=$this->_form;
        $mform->addElement('html',$this->_customdata['text']);
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('form_confirm','local_cool'));
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}