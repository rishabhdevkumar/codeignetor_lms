<?php

use App\Models\EmpmasterModel;
use App\Models\AssignRoleModel; 
use App\Models\NotificationModel;

if (! function_exists('send_mail')) {
    
    function send_mail($parameters = []) {
        $empMasterModel = new EmpmasterModel(); 
        $AssignRoleModel = new AssignRoleModel();
        $NotificationModel = new NotificationModel();
        $domainCategory = session()->get('domain_category');
        $data = [];

        foreach ($parameters as $key => $value) {
            if ($value) {
                $subject = $parameters['subject'];
                $title = $parameters['title'];
                switch ($key) {
                	case 'individual_emp':
                        //Logic to send mail for individual_emp...
                        $messages = isset($parameters['individual_emp_message']) ? $parameters['individual_emp_message'] : '';
                        $individual_emp_code = isset($parameters['individual_emp_code']) ? $parameters['individual_emp_code'] : '';

                        $indiv_emp = $empMasterModel->select('email_id')->whereIn('emp_code', $individual_emp_code)->get()->getResultArray();

                        $Indivi_emp_data = array_column($indiv_emp, 'email_id');
                        $data['individual_emp_code'] = implode(',', $Indivi_emp_data);

                        $email = \Config\Services::email();
                        $email->setFrom('no-reply@pakka.com', 'Team Pakka');
                        $email->setTo($data['individual_emp_code']);
                        //$email->setTo('siddharth.shrotri@hashtaglabs.in,siddharth.shrotri@yopmail.com');
                        $email->setCC('');
                        $email->setBCC('');
                        $email->setSubject($subject);                  
                        $templateData = ['message' => $messages, 'title'=>$title]; 
                        $message = view('email_template', $templateData);
                        $email->setMessage($message);
                        $email->setMailType('html');

                        if ($email->send()) {
                            // echo "Email sent successfully to Self";
                        } else {
                            // Display error message
                            // echo "Email sending failed. Error: " . $email->printDebugger(['headers']);
                        }

                        break;

                    case 'self_emp':
                        // Logic to send mail for self_emp...
                        $messages = isset($parameters['self_emp_message']) ? $parameters['self_emp_message'] : '';

                        $data['self_emp'] = $empMasterModel->select('email_id')->where('emp_code', session()->get('emp_code'))->getByDomainCategory($domainCategory)->get()->getRowArray();

                        $email = \Config\Services::email();
                        $email->setFrom('no-reply@pakka.com', 'Team Pakka');
                        $email->setTo($data['self_emp']->email_id);
                        // $email->setTo('siddharth.shrotri@hashtaglabs.in,siddharth.shrotri@yopmail.com');
                        $email->setCC('');
                        $email->setBCC('');
                        $email->setSubject($subject);                  
                        $templateData = ['message' => $messages, 'title'=>$title]; 
                        $message = view('email_template', $templateData);
                        $email->setMessage($message);
                        $email->setMailType('html');

                        if ($email->send()) {
                            // echo "Email sent successfully to Self";
                        } else {
                            // Display error message
                            // echo "Email sending failed. Error: " . $email->printDebugger(['headers']);
                        }

                        break;

                    case 'tls_emp':
                        // Logic to send mail for tls_emp...

                        $messages = isset($parameters['tls_emp_message']) ? $parameters['tls_emp_message'] : '';

                        $data['tls_emp'] = $empMasterModel->where('emp_code', session()->get('emp_code'))->getByDomainCategory($domainCategory)->get()->getRowArray();

                        $selectedroles = $AssignRoleModel->select('assign_emp')->where('role_id', 2)->get()->getFirstRow();
                        $assign_emp = json_decode($selectedroles->assign_emp, true);
                        $convertedData = "'" . implode("','", $assign_emp) . "'";
                        $psp_admin = $empMasterModel->select('email_id')->whereIn('emp_code', $assign_emp)->getByDomainCategory($domainCategory)->get()->getResultArray();
                        $emails = array_column($psp_admin, 'email_id');
                        $data['psp_email'] = implode(',', $emails);

                        $toEmails = [];
                        $ccEmails = [];
                        $bccEmails = [];
                        $notifications = [];

                        // Checking each value in 'to' array and adding corresponding email
                        if ($parameters['to']['emp_Leader']) {
                            $toEmails[] = $data['tls_emp']['leader_email'];
                            $notifications[] = $data['tls_emp']['leader_id'];
                        }
                        if ($parameters['to']['emp_SLeader']) {
                            $toEmails[] = $data['tls_emp']['sangh_leader_email'];
                            $notifications[] = $data['tls_emp']['sangh_leader_code'];
                        }
                        if ($parameters['to']['emp_PSP_LDP']) {
                            $toEmails[] = $data['psp_email'];
                            foreach ($assign_emp as $psp_code) {
                                $notifications[] = $psp_code;
                            }
                        }

                        if ($parameters['cc']['emp_Leader']) {
                            $ccEmails[] = $data['tls_emp']['leader_email'];
                        }
                        if ($parameters['cc']['emp_SLeader']) {
                            $ccEmails[] = $data['tls_emp']['sangh_leader_email'];
                        }
                        if ($parameters['cc']['emp_PSP_LDP']) {
                            $ccEmails[] = $data['psp_email'];
                        }

                        if ($parameters['bcc']['emp_Leader']) {
                            $bccEmails[] = $data['tls_emp']['leader_email'];
                        }
                        if ($parameters['bcc']['emp_SLeader']) {
                            $bccEmails[] = $data['tls_emp']['sangh_leader_email'];
                        }
                        if ($parameters['bcc']['emp_PSP_LDP']) {
                            $bccEmails[] = $data['psp_email'];
                        }

                        $ccEmails[] = $data['tls_emp']['pracharak_email'];

                         foreach ($notifications as $emp_code) {
                            $data = [
                                'emp_codes' => $emp_code,
                                'title' => $title,
                                'message' => $parameters['notification_message'],
                                'date' => date("Y/m/d"),
                                'url' => $parameters['notification_url']
                            ];

                            // Insert the data
                            $NotificationModel->insert($data);
                        }

                        $email = \Config\Services::email();

                        // From Address
                        $email->setFrom('no-reply@pakka.com', 'Team Pakka');

                        // To Address (validate emails)
                        $email->setTo($toEmails);
                        $email->setCC($ccEmails);
                        $email->setBCC($bccEmails);

                        // Subject
                        $email->setSubject($subject);

                        // Message Body
                        $templateData = ['message' => $messages, 'title' => $title];
                        $message = view('email_template', $templateData);
                        $email->setMessage($message);

                        // Mail Type
                        $email->setMailType('html');

                        // Send Email and Debug Errors
                        if ($email->send()) {
                            echo "Email sent successfully.";
                        } else {
                            // echo "Email sending failed. Debugging details:";
                            // echo "<pre>";
                            // print_r($email->printDebugger(['headers']));
                            // echo "</pre>";
                            
                        }

                        break;

                    case 'all_sangh_emp_email':
                        // Logic to send mail for all_sangh_emp_email...
                        $messages = isset($parameters['all_sangh_emp_email_message']) ? $parameters['all_sangh_emp_email_message'] : '';

                        $allSangh_emp = $empMasterModel->select('email_id')->where('sangh_code', session()->get('sangh'))->getByDomainCategory($domainCategory)->get()->getResultArray();
                        $Sanghemails = array_column($allSangh_emp, 'email_id');
                        $data['all_sangh_emp_email'] = implode(',', $Sanghemails);

                        $email = \Config\Services::email();
                        $email->setFrom('no-reply@pakka.com', 'Team Pakka');
                        $email->setTo($data['all_sangh_emp_email']);

                        // $email->setTo('siddharth.shrotri@hashtaglabs.in,siddharth.shrotri@yopmail.com');
                        $email->setCC('');
                        $email->setBCC('');
                        $email->setSubject($subject);                  
                        $templateData = ['message' => $messages, 'title'=>$title]; 
                        $message = view('email_template', $templateData);
                        $email->setMessage($message);
                        $email->setMailType('html');

                        if ($email->send()) {
                            // echo "Email sent successfully to Sangh's all employees";
                        } else {
                            // Display error message
                            // echo "Email sending failed. Error: " . $email->printDebugger(['headers']);
                        }


                        break;

                    case 'psp_email':
                        // Logic to send mail for psp_email...
                        $messages = isset($parameters['psp_email_message']) ? $parameters['psp_email_message'] : '';

                        $selectedroles = $AssignRoleModel->select('assign_emp')->where('role_id', 2)->get()->getFirstRow();
                        $assign_emp = json_decode($selectedroles->assign_emp, true);
                        $convertedData = "'" . implode("','", $assign_emp) . "'";
                        $psp_admin = $empMasterModel->select('email_id')->whereIn('emp_code', $assign_emp)->getByDomainCategory($domainCategory)->get()->getResultArray();
                        $emails = array_column($psp_admin, 'email_id');
                        $data['psp_email'] = implode(',', $emails);

                        $email = \Config\Services::email();
                        $email->setFrom('no-reply@pakka.com', 'Team Pakka');
                        $email->setTo($data['psp_email']);

                        // $email->setTo('siddharth.shrotri@hashtaglabs.in,siddharth.shrotri@yopmail.com');
                        $email->setCC('');
                        $email->setBCC('');
                        $email->setSubject($subject);                  
                        $templateData = ['message' => $messages, 'title'=>$title]; 
                        $message = view('email_template', $templateData);
                        $email->setMessage($message);
                        $email->setMailType('html');

                        if ($email->send()) {
                            // echo "Email sent successfully to PSP-LDP Admin";
                        } else {
                            // Display error message
                            // echo "Email sending failed. Error: " . $email->printDebugger(['headers']);
                        }


                        break;
                    
                    case 'expiresop':
                        // Logic to send mail for all_sangh_emp_email...
                        $messages = isset($parameters['expiresop_message']) ? $parameters['expiresop_message'] : '';

                        $allSangh_emp = $empMasterModel->select('email_id, emp_code')->where('emp_code', $parameters['emp_code'])->get()->getResultArray();

                        $Sanghemails = array_column($allSangh_emp, 'email_id');
                        $sangh_emp_email = implode(';', $Sanghemails);

                        $all_emp_code = array_column($allSangh_emp, 'emp_code');

                        foreach ($all_emp_code as $emp_code) {
                            $data = [
                                'emp_codes' => $emp_code,
                                'title' => $title,
                                'message' => $parameters['notification_message'],
                                'date' => date("Y/m/d"),
                                'url' => $parameters['notification_url']
                            ];
                            $NotificationModel->insert($data);
                        }
                        
                        $email = \Config\Services::email();
                        $email->setFrom('no-reply@pakka.com', 'Team Pakka');
                        $email->setTo($sangh_emp_email);
                        $email->setCC('');
                        $email->setSubject($subject);                  
                        $templateData = ['message' => $messages, 'title'=>$title]; 
                        $message = view('email_template', $templateData);
                        $email->setMessage($message);
                        $email->setMailType('html');

                        if ($email->send()) {
                            // echo "Email sent successfully to Sangh's all employees";
                        } else {
                            // Display error message
                            // echo "Email sending failed. Error: " . $email->printDebugger(['headers']);
                        }


                        break;    

                    // Add cases for other parameters as needed...
                }
            }
        }

        return $data;
    }
}


