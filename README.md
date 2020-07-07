![mailketinglogo](https://mailketing.co.id/wp-content/uploads/2020/05/mailketing.png)


# Laravel Driver for Mailketing

Sebuah Email Driver API untuk Laravel

# Table of Content
  
* [Installation](#installation)
* [Quick Start](#quick-start)
* [Usage of library in Project](#inproject)
* [Sample Example](#eg)

<a name="installation"></a>
# Installation

<a name="prereq"></a>

### Prerequisites

[PHP > 7.1.3](https://www.php.net/manual/en/install.php)

[Composer v1.8](https://getcomposer.org/download/)

[Laravel 5.8](https://laravel.com/docs/5.8/installation)

guzzlehttp/guzzle 6.2.0

A free account on Pepipost. If you don't have a one, [click here](https://app.pepipost.com) to signup.

## Usage

### Configuring laravel project

#### Step 1 - Buat Laravel Project / Gunakan Yang lama 

```bash 
laravel new testproject
```

#### Step 2 - Menuju Folder Project dan install dengan Composer.


```bash
$ composer require mailketing/mailketing-laravel
```

#### Step 3 - Configurations 

1) tambahkan code dibawah di file config/services.php


    ```php
        'mailketing' => [
            'api_key' => env('MAILKETING_API_TOKEN'),
        ],
     ```
 


2) Tambahkan Code dibawah di .env file

      ```dotenv
      MAIL_DRIVER=mailketing
      MAILKETING_API_TOKEN='API_TOKEN_ANDA'
      ```

#### Step 4-  Cara Testing, Anda sudah dapat menggunakan mail send laravel seperti biasa dan otomatis akan via mailketing

      Mail::send('contoh konten',$data, function ($message) {
          $message
              ->to('foo@example.com', 'foo_name')
              ->from('sender@example.com', 'sender_name')
              ->subject('subject');
      });

      echo 'Email sent successfully';
      
      ```

3) Create Route in routes/web.php

      ```php

      Route::get('/send/email', 'TestController@sendMail')->name('sendEmail');

      ```

#### Step 5 - Testing

Host your laravel project and enter url- http://your_url.com/send/email in browser

This will send email and display Email sent successfully on browser.

#### Additional Usage

IF want to pass others parameters of Pepipost SendEmail API use embedData function and include below code as below
Add parameters as per your requirement. Do not use multiple to's,cc's,bcc's with this method.

```php
function sendMail(){

Mail::send('viewname.name',$data, function ($message) {
    $message
        ->to('foo@example.com', 'foo_name')
        ->from('sender@example.com', 'sender_name')
        ->subject('subject')
        ->cc('cc@example.com','recipient_cc_name')
        ->bcc('recipient_bcc@example.com','recipient_bcc_name')
        ->replyTo('reply_to@example.com','recipient_bcc')
        ->attach('/myfilename.pdf')
        ->embedData([
            'personalizations' => ['attributes'=>['ACCOUNT_BAL'=>'String','NAME'=>'NAME'],'x-apiheader'=>'x-apiheader_value','x-apiheader_cc'=>'x-apiheader_cc_value'],'settings' => ['bcc'=>'bccemail@gmail.com','clicktrack'=>1,'footer'=>1,'opentrack'=>1,'unsubscribe'=>1 ],'tags'=>'tags_value','templateId'=>''
        ],'pepipostapi');
        
 return 'Email sent successfully';
}       

```

For multiple to's,cc's,bcc's pass recipient,recipient_cc,recipient_bcc as below, create personalizations as required

```php


function sendMail(){

Mail::send('viewname.name',$data, function ($message) {
    $message
        ->from('sender@example.com', 'sender_name')
        ->subject('subject')
        ->replyTo('reply_to'@example.com,'recipient_bcc')
        ->attach('/myfilename.pdf')
        ->embedData([
                    'personalizations' => [['recipient'=>'foo@example.com','attributes'=>['ACCOUNT_BAL'=>'String','NAME'=>'name'],'recipient_cc'=>['cc@example.com','cc2@example.com'],'recipient_bcc'=>['bcc@example.com','bcc2@example.com'],'x-apiheader'=>'x-apiheader_value','x-apiheader_cc'=>'x-apiheader_cc_value'],['recipient'=>'foo@example.com','attributes'=>['ACCOUNT_BAL'=>'String','NAME'=>'name'],'x-apiheader'=>'x-apiheader_value','x-apiheader_cc'=>'x-apiheader_cc_value']],'settings' => ['bcc'=>'bccemail@gmail.com','clicktrack'=>1,'footer'=>1,'opentrack'=>1,'unsubscribe'=>1 ],'tags'=>'tags_value','templateId'=>''
                ],'pepipostapi');
        });
        
return 'Email sent successfully';
}

```

