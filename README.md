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

