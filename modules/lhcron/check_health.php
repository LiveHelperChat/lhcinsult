<?php

// Run me every 1 minute
// /usr/bin/php cron.php -s site_admin -e lhcinsult -c cron/check_health

$lhciOptions = erLhcoreClassModelChatConfig::fetch('lhcinsult_options');
$dataOptions = (array)$lhciOptions->data;

// Continue here
if (isset($dataOptions['enabled']) && $dataOptions['enabled'] == true) {
    if (!(isset($dataOptions['disable_in_msg']) && $dataOptions['disable_in_msg'] == 1))
    {
        $response = erLhcoreClassLhcinsultWorker::isInsult('you are stupid', $dataOptions, -1, 1);
        if ($response['error'] === true) {
            $dataOptions['disable_in_msg'] = true;

            if (isset($dataOptions['report_email_in']) && !empty($dataOptions['report_email_in']))
            {
                $mail = new PHPMailer();
                $mail->CharSet = "UTF-8";
                $mail->FromName = 'Live Helper Chat Messages Insult Detection Disabled';
                $mail->Subject = 'Insult detection was disabled because of an error';
                $mail->Body = print_r($response, true);

                $emailRecipient = explode(',',$dataOptions['report_email_in']);

                foreach ($emailRecipient as $receiver) {
                    $mail->AddAddress( trim($receiver) );
                }
                erLhcoreClassChatMail::setupSMTP($mail);
                $mail->Send();
            }

            // Update Options
            $lhciOptions->explain = '';
            $lhciOptions->type = 0;
            $lhciOptions->hidden = 1;
            $lhciOptions->identifier = 'lhcinsult_options';
            $lhciOptions->value = serialize($dataOptions);
            $lhciOptions->saveThis();

            // Clean jobs queue to avoid taking jobs queue
            erLhcoreClassRedis::instance()->del('resque:queue:lhc_insult');

            echo "Messages service - DISABLED\n";
        } else {
            echo "Messages service - OK\n";
        }
    } else { // Service is disabled, check is it alive
        $response = erLhcoreClassLhcinsultWorker::isInsult('you are stupid', $dataOptions, -1, 1);
        if ($response['error'] === false) {
            if (isset($dataOptions['auto_enable']) && $dataOptions['auto_enable'] == 1) {
                $dataOptions['disable_in_msg'] = false;

                if (isset($dataOptions['report_email_in']) && !empty($dataOptions['report_email_in']))
                {
                    $mail = new PHPMailer();
                    $mail->CharSet = "UTF-8";
                    $mail->FromName = 'Live Helper Chat Messages Insult Detection Enabled';
                    $mail->Subject = 'Insult detection was enabled';
                    $mail->Body = print_r($response, true);

                    $emailRecipient = explode(',',$dataOptions['report_email_in']);

                    foreach ($emailRecipient as $receiver) {
                        $mail->AddAddress( trim($receiver) );
                    }
                    erLhcoreClassChatMail::setupSMTP($mail);
                    $mail->Send();
                }

                // Update Options
                $lhciOptions->explain = '';
                $lhciOptions->type = 0;
                $lhciOptions->hidden = 1;
                $lhciOptions->identifier = 'lhcinsult_options';
                $lhciOptions->value = serialize($dataOptions);
                $lhciOptions->saveThis();
                echo "Message service - ENABLED\n";
            } else {
                echo "Message service - OK, but not auto enabled\n";
            }
        } else {
            echo "Message service - STILL NOT OK\n";
        }
    }
}

if (isset($dataOptions['enabled_img']) && $dataOptions['enabled_img'] == true) {

    if (!(isset($dataOptions['disable_in_img']) && $dataOptions['disable_in_img'] == 1))
    {
        $response = erLhcoreClassLhcinsultWorker::isNudeRestAPI('test.jpg', 'design/defaulttheme/images/general/logo.png', 0, $dataOptions['host_img'], 1);

        if ($response['scanned'] === false) {

            $dataOptions['disable_in_img'] = true;

            if (isset($dataOptions['report_email_in']) && !empty($dataOptions['report_email_in']))
            {
                $mail = new PHPMailer();
                $mail->CharSet = "UTF-8";
                $mail->FromName = 'Live Helper Chat Images Insult Detection Disabled';
                $mail->Subject = 'Insult detection was disabled because of an error';
                $mail->Body = print_r($response, true);

                $emailRecipient = explode(',',$dataOptions['report_email_in']);

                foreach ($emailRecipient as $receiver) {
                    $mail->AddAddress( trim($receiver) );
                }

                erLhcoreClassChatMail::setupSMTP($mail);
                $mail->Send();
            }

            // Update Options
            $lhciOptions->explain = '';
            $lhciOptions->type = 0;
            $lhciOptions->hidden = 1;
            $lhciOptions->identifier = 'lhcinsult_options';
            $lhciOptions->value = serialize($dataOptions);
            $lhciOptions->saveThis();
            echo "IMG service - DISABLED\n";
        } else {
            echo "IMG service - OK\n";
        }

    } else {
        $response = erLhcoreClassLhcinsultWorker::isNudeRestAPI('test.jpg', 'design/defaulttheme/images/general/logo.png', 0, $dataOptions['host_img'], 1);

        if ($response['scanned'] === true) {
            if (isset($dataOptions['auto_enable']) && $dataOptions['auto_enable'] == 1) {
                $dataOptions['disable_in_img'] = false;

                if (isset($dataOptions['report_email_in']) && !empty($dataOptions['report_email_in']))
                {
                    $mail = new PHPMailer();
                    $mail->CharSet = "UTF-8";
                    $mail->FromName = 'Live Helper Chat Images Insult Detection Enabled';
                    $mail->Subject = 'Insult images detection was enabled';
                    $mail->Body = print_r($response, true);

                    $emailRecipient = explode(',',$dataOptions['report_email_in']);

                    foreach ($emailRecipient as $receiver) {
                        $mail->AddAddress( trim($receiver) );
                    }
                    erLhcoreClassChatMail::setupSMTP($mail);
                    $mail->Send();
                }

                // Update Options
                $lhciOptions->explain = '';
                $lhciOptions->type = 0;
                $lhciOptions->hidden = 1;
                $lhciOptions->identifier = 'lhcinsult_options';
                $lhciOptions->value = serialize($dataOptions);
                $lhciOptions->saveThis();

                echo "IMG service - ENABLED\n";
            } else {
                echo "IMG service - OK, but not auto enabled\n";
            }

        } else {
            echo "IMG service - STILL NOT OK\n";
        }
    }
}
