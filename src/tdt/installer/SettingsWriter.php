<?php

namespace tdt\installer;

/**
 * Writes input to the session.
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class SettingsWriter
{ 
    public function writeData($data, $session)
    {
        foreach($data as $key => $value)
        {
            $session->set($key, $value);
        }
    }
}