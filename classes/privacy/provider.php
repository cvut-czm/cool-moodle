<?php
/**
 * Created by CTU CZM.
 * Author: Jiri Fryc
 * License: GNU GPLv3
 */

namespace local_cool\privacy;


class provider implements \core_privacy\local\metadata\null_provider
{

    /**
     * Get the language string identifier with the component's language
     * file to explain why this plugin stores no data.
     *
     * @return  string
     */
    public static function get_reason(): string
    {
        return get_string('privacy:metadata','local_cool');
    }
}