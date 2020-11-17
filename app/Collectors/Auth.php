<?php

/**
 * --------------------------------------------------------------------
 * CODEIGNITER 4 - SimpleAuth
 * --------------------------------------------------------------------
 *
 * This content is released under the MIT License (MIT)
 *
 * @package    SimpleAuth
 * @author     GeekLabs - Lee Skelding 
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       https://github.com/GeekLabsUK/SimpleAuth
 * @since      Version 1.0
 * 
 */

namespace App\Collectors;

use CodeIgniter\Debug\Toolbar\Collectors\BaseCollector;

class Auth extends BaseCollector
{
    /**
     * Whether this collector has data that can
     * be displayed in the Timeline.
     *
     * @var boolean
     */
    protected $hasTimeline = false;

    /**
     * Whether this collector needs to display
     * content in a tab or not.
     *
     * @var boolean
     */
    protected $hasTabContent = true;

    /**
     * Whether this collector has data that
     * should be shown in the Vars tab.
     *
     * @var boolean
     */
    protected $hasVarData = false;

    /**
     * The 'title' of this Collector.
     * Used to name things in the toolbar HTML.
     *
     * @var string
     */
    protected $title = 'Auth';

    //--------------------------------------------------------------------

    public function display(): string   {
        

       if(session()->get('isLoggedIn')){

            $id = session()->get('id');
            $firstname = session()->get('firstname');
            $lastname = session()->get('lastname');
            $email = session()->get('email');
            $role = session()->get('role');
            $superadmin =  session()->get('isSuperAdmin');
        
            $html = '<h3>Current User</h3>';
            $html .= '<table><tbody>';
            $html .= "<tr><td style='width:150px;'>User ID</td><td>{$id}</td></tr>";
            $html .= "<tr><td>First Name</td><td>{$firstname}</td></tr>";
            $html .= "<tr><td>Last Name</td><td>{$lastname}</td></tr>";
            $html .= "<tr><td>Email</td><td>{$email}</td></tr>";
            $html .= "<tr><td>Role</td><td>{$role}</td></tr>";
            $html .= "<tr><td>Is Super Admnin</td><td>{$superadmin}</td></tr>";             
            $html .= '</tbody></table>';
        } else {
            $html = '<p>Not logged in.</p>';
        }
        return $html;
    }

    public function icon(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADLSURBVEhL5ZRLCsIwGAa7UkE9gd5HUfEoekxxJx7AhXoCca/fhESkJiQxBHwMDG3S/9EmJc0n0JMruZVXK/fMdWQRY7mXt4A7OZJvwZu74hRayIEc2nv3jGtXZrOWrnifiRY0OkhiWK5sWGeS52bkZymJ2ZhRJmwmySxLCL6CmIsZZUIixkiNezCRR+kSUyWH3Cgn6SuQIk2iuOBckvN+t8FMnq1TJloUN3jefN9mhvJeCAVWb8CyUDj0vxc3iPFHDaofFdUPu2+iae7nYJMCY/1bpAAAAABJRU5ErkJggg==';
    }

}