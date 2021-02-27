<?php

/**
* --------------------------------------------------------------------
* CODEIGNITER 4 - EmailSender
* --------------------------------------------------------------------
*
* This content is released under the MIT License (MIT)
*
* @package    EmailSender
* @author     GeekLabs <geeklabsonline@gmail.com>
* @license    https://opensource.org/licenses/MIT	MIT License
* @link       https://github.com/GeekLabsUK/SimpleAuth
* @since      Version 1.0
* @category   PHP
* @php        7.3
*
*/

namespace App\Libraries;

/**
 * SendEmail
 */
class SendEmail
{
    
    /**
     * build
     *
     * @param  mixed $emaildata
     * @return void
     */
    public function build($emaildata)
    {
        $email = \Config\Services::email();              

        $email->setTo($emaildata['to']);
        $email->setFrom($emaildata['fromEmail'],$emaildata['fromName']);
        $email->setSubject($emaildata['subject']);
        $email->setMessage(view('emails/'.$emaildata['message_view'], $emaildata['messagedata']));

        if (!$email->send()) {

            $this->error = 'email not sent';
            return  false;
        }
        
        return  true;
        
        
    }


   
}
