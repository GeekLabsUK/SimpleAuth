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

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model{

    protected $table = 'users';
    protected $allowedFields = ['firstname', 'lastname', 'email', 'password', 'reset_token', 'reset_expire', 'activated', 'activate_token', 'activate_expire', 'role', 'updated_at', 'deleted_at'];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];


    protected function beforeInsert(array $data){

        $data = $this->passwordHash($data);
        return $data;
      
    }

    protected function beforeUpdate(array $data)
    {

        $data = $this->passwordHash($data);
        return $data;
    }

    protected function passwordHash(array $data)
    {

        if (isset($data['data']['password']))
        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_ARGON2ID);
        return $data;

    }

   


}
