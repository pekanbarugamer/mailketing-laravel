<?php
namespace Mailketing\MailketingLaravelDriver\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Mail\Transport\Transport;
use Swift_Attachment;
use Swift_Image;
use Swift_Mime_SimpleMessage;
use Swift_MimePart;

class MailketingTransport extends Transport
{


    const SMTP_API_NAME = 'mailketingapi';
    const MAXIMUM_FILE_SIZE = 20480000;
    const BASE_URL = 'https://app.mailketing.co.id/api/v2/send';

    /**
     * @var Client
     */
    private $client;
    private $attachments;
    private $numberOfRecipients;
    private $apiKey;
    private $endpoint;

    public function __construct(ClientInterface $client, $api_key, $endpoint = null)
    {
        $this->client = $client;
        $this->apiKey = $api_key;
        $this->endpoint = isset($endpoint) ? $endpoint : self::BASE_URL;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message,&$failedRecipients = null)
    {

        $content = [
            'subject'          => $message->getSubject(),
        ];
        if ($message->getFrom()) {
             foreach ($message->getFrom() as $email => $name) {
                 $content['from_name']=$name;
                 $content['from_email']=$email;
             }
         }

         if($message->getTo()){
               $x = $this->getTo($message);
       }

        if ($contents = $this->getContents($message)) {
            $content['html'] = $contents;
        }
        $mailketingMessage = [
            'content'    => $content,
            'recipients' => $x,
        ];

        $attachments = $this->getAttachments($message);
        if (count($attachments) > 0) {
            $$mailketingMessage['content']['attachments'] = $attachments;
        }
        $mailketingMessage['api_token']=$this->apiKey;
        print_r($mailketingMessage);
        $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://app.mailketing.id/api/v2/send");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($mailketingMessage));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec ($ch);
                print_r($output);
                curl_close ($ch);
    }

    /**
     * @param Swift_Mime_SimpleMessage $message
     * @return array
     */
     private function getPersonalizations(Swift_Mime_SimpleMessage $message)
     {
         $setter = function (array $addresses) {
             $recipients = [];
             foreach ($addresses as $email => $name) {
               $recipients = [
                                        'address'   => ['email' => $email],
                                    ];
             }
             return $recipients;
         };
 	$personalization= $this->getTo($message);

         return $personalization;
     }


      /**
      * Get From Addresses.
      *
      * @param Swift_Mime_SimpleMessage $message
      * @return array
      */
     private function getTo(Swift_Mime_SimpleMessage $message)
     {

 	$this->numberOfRecipients=0;
         if ($message->getTo()) {
 	    $recipient = [];
             foreach ($message->getTo() as $email => $name) {
 		$recipient = [
                            'address'   => ['email' => $email],
                        ];
         	}

    }
         return $recipient;
 }


     /**
     * Get From Addresses.
     *
     * @param Swift_Mime_SimpleMessage $message
     * @return array
     */

      /**
     * Get From Addresses.
     *
     * @param Swift_Mime_SimpleMessage $message
     * @return array
     */
    private function getCC(Swift_Mime_SimpleMessage $message)
    {
        $ccarray = array();
        if ($message->getCc()) {
            foreach ($message->getCc() as $email => $name) {
                $ccarray[] = $email;
            }
        }
        return $ccarray;
    }

    /**
     * Get From Addresses.
     *
     * @param Swift_Mime_SimpleMessage $message
     * @return array
     */
    private function getBCC(Swift_Mime_SimpleMessage $message)
    {
        $bccarray = array();
        if ($message->getBcc()) {
            foreach ($message->getBcc() as $email => $name) {
                $bccarray[] = $email;
            }
        }
        return $bccarray;
    }




    /**
     * Get ReplyTo Addresses.
     *
     * @param Swift_Mime_SimpleMessage $message
     * @return array
     */
    private function getReplyTo(Swift_Mime_SimpleMessage $message)
    {
        if ($message->getReplyTo()) {
            foreach ($message->getReplyTo() as $email => $name) {
                return $email;
            }
        }
        return null;
    }

    /**
     * Get contents.
     *
     * @param Swift_Mime_SimpleMessage $message
     * @return array
     */
   private function getContents(Swift_Mime_SimpleMessage $message)
    {
        return $message->getBody();
    }

    /**
     * @param Swift_Mime_SimpleMessage $message
     * @return array
     */
    private function getAttachments(Swift_Mime_SimpleMessage $message)
    {
       $attachments = [];
       foreach ($message->getChildren() as $attachment) {
        $attachment = $message->getChildren();
	 if ((!$attachment instanceof Swift_Attachment && !$attachment instanceof Swift_Image)
		|| $attachment->getFilename() === self::SMTP_API_NAME
                || !strlen($attachment->getBody()) > self::MAXIMUM_FILE_SIZE
            ) {
                continue;
            }
            $attachments[] = [
                'fileContent'     => base64_encode($attachment->getBody()),
                'fileName'    => $attachment->getFilename(),
            ];
       }
        return $this->attachments = $attachments;
    }

    /**
     * Set Request Body Parameters
     *
     * @param Swift_Mime_SimpleMessage $message
     * @param array $data
     * @return array
     * @throws \Exception
     */
    protected function setParameters(Swift_Mime_SimpleMessage $message, $data)
    {
       //$this->numberOfRecipients = 0;
       $smtp_api = [];
       foreach ($message->getChildren() as $attachment) {
            if (!$attachment instanceof Swift_Image || !in_array(self::SMTP_API_NAME, [$attachment->getFilename(), $attachment->getContentType()])) {
                continue;
            }
            $smtp_api = $attachment->getBody();
        }
        foreach ($smtp_api as $key => $val) {
            switch ($key) {

                case 'settings':
                    $this->setSettings($data, $val);
                    continue 2;
		case 'tags':
		    array_set($data,'tags',$val);
		    continue 2;
		case 'templateId':
		    array_set($data,'templateId',$val);
		    continue 2;
                case 'personalizations':
                    $this->setPersonalizations($data, $val);
                    continue 2;

                case 'attachments':
                    $val = array_merge($this->attachments, $val);
                    break;
                    }


           array_set($data, $key, $val);
        }
        return $data;
    }

    private function setPersonalizations(&$data, $personalizations)
    {

        foreach ($personalizations as $index => $params) {

	    if($this->numberOfRecipients <= 0)
	    {
		array_set($data,'personalizations'.'.'.$index  , $params);
		continue;
	    }
	    $count=0;
	    while($count<$this->numberOfRecipients)
	    {
                if (in_array($params, ['attributes','x-apiheader','x-apiheader_cc'])&& !in_array($params, ['recipient','recipient_cc'])) {
		      array_set($data, 'personalizations.'.$count . '.' . $index  , $params);
                } else {
			array_set($data, 'personalizations.'.$count . '.' . $index  , $params);
                }
		$count++;
       	 }
	}
    }

    private function setSettings(&$data, $settings)
    {
        foreach ($settings as $index => $params) {
        	array_set($data,'settings.'.$index,$params);
	}
    }

    /**
     * @param $payload
     * @return Response
     */
    private function post($payload)
    {
        return $this->client->post($this->endpoint, $payload);
    }
}
