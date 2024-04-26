<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class CustomConfigServiceProvider extends ServiceProvider

{



    /**

     * Bootstrap any application services.

     *

     * @return void

     */

    public function boot()

    {
        $this->app['config']['TYREDB'] = DB::connection('tyredb');
        $this->app['config']['INVOICEDB'] = DB::connection('jansinvoicedb');
        $this->app['config']['INQDB'] = DB::connection('inqdb');
        $this->app['config']['DEFAULTLANG'] = env("LANGUAGE_ID",1);
        $this->app['config']['PER_PAGE'] = 20;
        $this->app['config']['DURATION'] = 2 * 24 * 60; // Set for two days
       
        $this->app['config']['IMAGEPATH'] = [

            'S3_SLIDER_PATH' => env('S3_SLIDER_PATH', 'https://s3.eu-central-1.amazonaws.com/jansnewfiles/janjapan/sliders/'),
            'S3_IMAGE_PATH'  => env('S3_IMAGE_PATH', 'https://s3.eu-central-1.amazonaws.com/jansnewfiles/common/car_images/'),
            'STAFF_IMAGE_PATH' =>  'https://janjapan.com/uploads/staff/',
            'RESOURCES_IMAGE_PATH' =>  'https://janjapan.com/resources/images/',
            'CUSTOMER_REVIEW_PATH' => 'https://s3.eu-central-1.amazonaws.com/jansnewfiles/customer_review/customer_review/uploads/customer_review_files/',

        ]; 
        
        $this->app['config']['SHARJAHCONTACTINFO'] = [
            'area' => 'Industrial Area 10',
            'building' =>'Al-Ramla Building',
            'furtheraddress' => 'Opposite old Epco Patrol Station - Sharjah - United Arab Emirates',
            'googleMap' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3606.9414548800382!2d55.4078293150117!3d25.306170983845675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x89708a27ddbb3f1e!2sJan+Japan+(Abr+Al+Japan+Used+Cars+%26+Spare+Parts+TR.+LLC)!5e0!3m2!1sen!2s!4v1445941538175'
          ];
        
        $this->app['config']['IS_SALE'] = [1,3];
        $this->app['config']['IS_DISPLAY'] = [1,0];
        $this->app['config']['OR_IS_SALE'] = ['cr.is_sale' => 2];
        $this->app['config']['PHILIPPINE_COUNTRY_IDS'] = [462,635,580,524,785];
        $this->app['config']['DUBAI_SPECIAL_CITY_IDS'] = [1301];
        $this->app['config']['AUCTION_COUNTRY_IDS'] = [17];


        $this->app['config']['ALL_STOCK_IN_JAPAN'] = 'cr.parent_id IN(369,482,370,392,498,371,373,399,374,367,468,375,464,377,378,380,381,382,383,384,535,385,
		543,487,545,544,542)';
       
        $this->app['config']['ALL_NEW_LOADING'] = 'cr.parent_id IN(346,483,352,403,555,342,356,400,347,345,469,488,350,465,527,340,344,338,348,341,343,349,536,339)';
        


        $this->app['config']['mail'] = [
            'default' => env('MAIL_MAILER', 'smtp'),
            'mailers' => [
                'smtp' => [
                    'transport' => 'smtp',
                    'host' => env('MAIL_HOST'), //'smtpout.secureserver.net',
                    'port' => env('MAIL_PORT'),
                    'encryption' => env('MAIL_ENCRYPTION'),
                    'username' => env('MAIL_USERNAME'),
                    'password' => env('MAIL_PASSWORD'),
                    'timeout' => null,
                    'local_domain' => env('MAIL_EHLO_DOMAIN'),
                ],
        
                'ses' => [
                    'transport' => 'ses',
                ],
        
                'mailgun' => [
                    'transport' => 'mailgun',
                ],
        
                'postmark' => [
                    'transport' => 'postmark',
                ],
        
                'sendmail' => [
                    'transport' => 'sendmail',
                    'path' => '/usr/sbin/sendmail -bs -i',
                ],
        
                'log' => [
                    'transport' => 'log',
                    'channel' => env('MAIL_LOG_CHANNEL'),
                ],
        
                'array' => [
                    'transport' => 'array',
                ],
        
                'failover' => [
                    'transport' => 'failover',
                    'mailers' => [
                        'smtp',
                        'log',
                    ],
                ],
            ],

            'from' => [
                'address' => env('MAIL_FROM_ADDRESS'),
                'name' => env('MAIL_FROM_NAME'),
            ],

        ];

        $this->app['config']['services'] = [

            'mailgun' => [
                'domain' => env('MAILGUN_DOMAIN'),
                'secret' => env('MAILGUN_SECRET'),
                'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
                'scheme' => 'https',
            ],
        
            'postmark' => [
                'token' => env('POSTMARK_TOKEN'),
            ],
        
            'ses' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            ],

        ];


    }



    /**

     * Register any application services.

     *

     * @return void

     */

    public function register()

    {

        //

    }



}

