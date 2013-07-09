<?php

namespace tdt\installer;

/**
 * Adds database settings input controls to a FormBuilder
 *
 * @author Benjamin Mestdagh
 * @copyright 2013 by 0KFN Belgium
 */
class DatabaseSettingsElementBuilder
{
    const numberOfSteps = 4;

    public function addElements($step, $formBuilder) 
    {
        switch($step)
        {
            case 1:
                $formBuilder->add('dbinstalldefault', 'radio', array(
                        'label' => 'Default',
                        'value' => 'default',
                        'data' => true,
                        'required' => false,
                    ));
                $formBuilder->add('dbrootpassword', 'password', array(
                        'label' => 'Root password',
                        'required' => false,
                    ));
                $formBuilder->add('dbinstalladvanced', 'radio', array(
                        'label' => 'Advanced',
                        'value' => 'advanced',
                        'required' => false,
                    ));
                break;
            case 2:
                $formBuilder->add('dbsystem', 'choice', array(
                        'label' => 'System',
                        'choices' => $this->getDatabaseChoices(),
                    ));
                $formBuilder->add('dbhost', 'text', array(
                        'label' => 'Host',
                        'data' => 'localhost',
                    ));
                break;
            case 3:
                $formBuilder->add('dbnewuser', 'radio', array(
                        'label' => 'New user',
                        'value' => 'dbnewuser',
                        'data' => true,
                        'required' => false,
                    ));
                $formBuilder->add('dbrootpassword', 'password', array(
                        'label' => 'Root password',
                        'required' => false,
                    ));
                $formBuilder->add('dbnewusername', 'text', array(
                        'label' => 'New username',
                        'required' => false,
                    ));
                $formBuilder->add('dbnewpassword', 'password', array(
                        'label' => 'New user password',
                        'required' => false,
                    ));
                $formBuilder->add('dbexistinguser', 'radio', array(
                        'label' => 'Existing user',
                        'value' => 'dbexistinguser',
                        'required' => false,
                    ));
                $formBuilder->add('dbuser', 'text', array(
                        'label' => 'Username',
                        'required' => false,
                    ));
                $formBuilder->add('dbpassword', 'password', array(
                        'label' => 'Password',
                        'required' => false,
                    ));
                break;
            case 4:
                $formBuilder->add('dbnewdb', 'radio', array(
                        'label' => 'New database',
                        'value' => 'dbnewdb',
                        'data' => true,
                        'required' => false,
                    ));
                $formBuilder->add('dbrootpassword', 'password', array(
                        'label' => 'Root password',
                        'required' => false,
                    ));
                $formBuilder->add('dbnewname', 'text', array(
                        'label' => 'New database name',
                        'required' => false,
                    ));
                $formBuilder->add('dbexistingdb', 'radio', array(
                        'label' => 'Existing database',
                        'value' => 'dbexistingdb',
                        'required' => false,
                    ));
                $formBuilder->add('dbname', 'text', array(
                        'label' => 'Database name',
                        'required' => false,
                    ));
                break;
            default:
                break;
        }
        
        return $formBuilder;
    }
    
    /**
     * Finds the installed PDO extensions in PHP.
     * @return array
     */
    private function getDatabaseChoices()
    {
        $result = array();
        $extensions = get_loaded_extensions();
        
        $values = preg_grep("/pdo.+|PDO.+/", $extensions);
        foreach($values as $value)
        {
            $item = substr($value, 4);
            $result[$item] = $item;
        }
        
        return $result;
    }
}