<?php
/**
 * @author bmxnt <bmxnt@mediasite.ru>
 * @copyright Mediasite LLC (http://www.mediasite.ru/)
 */

class ApiForms extends MSBaseApi {
    const TARGET_CALL = 1;
    public function callAction() {
        $data = array_intersect_key($_POST,
            array_flip(
                array(
                    'phone', 'fio', 'formid'
                )
            )
        );

        // Validate data
        $validator = new Validator($data);
        $validator->rule('empty', 'formid')->message('Некорректный идентификатор формы');
        $validator->rule('required', 'phone')->message('Поле не заполнено');
        $validator->rule('phone', 'phone')->message('Некорректный номер телефона');
 
        if($validator->validate()) {
            if(empty($data['fio'])) $data['fio'] = 'Личный номер';

            unset($data['formid']);
            // Send to subscribers
            $mailers = MSCore::db()->getCol(
                'SELECT mail FROM `'.PRFX.'mailer` WHERE type = '.self::TARGET_CALL.' OR type = 0'
            );
            $data['date'] = date('Y-m-d H:i:s');
            MSCore::db()->insert(PRFX.'order_call', $data);
            if(is_array($mailers) && !empty($mailers)) {
                // Send email
                $sendMail = new SendMail();
                $sendMail->init();
                $sendMail->setSubject('Обратный звонок на '.DOMAIN);
                $sendMail->setFrom('noreply@'.DOMAIN, 'Первая кровельная');

                // Prepare body
                $message = template('email/call',
                    array(
                        'data' => $data
                    )
                );

                $sendMail->setMessage($message);
                foreach($mailers as $_email) {
                    $sendMail->setTo($_email);
                    $sendMail->send();
                }
                unset($sendMail);
            }

            $content = template('ajax/success/call');
            $this->addData(array('content' => $content));
        } else {
            $errors = $validator->errors();
            foreach($errors as $_name => $_error) {
                if(is_array($_error)) {
                    $errors[$_name] = reset($_error);
                }
            }
            $this->errorAction(1001, 'Некорректно заполненные поля', array('errors' => $errors));
        }
    }
}