<?php
$config = [
    // This is a authentication source which handles admin authentication.
    'admin' => [
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.
        'core:AdminPassword',
    ],
    /* This is the name of this authentication source, and will be used to access it later. */
    'default-sp' => [
        'saml:SP',
		'privatekey' =>  '/var/simplesamlphp/cert/saml.pem',
        'certificate' => '/var/simplesamlphp/cert/saml.crt',
        'contacts' => array(
			array(
				'contactType'       => 'support',
				'emailAddress'      => 'mailto:support@inacademia.org',
				'givenName'         => 'InAcademia',
				'surName'           => 'Support',
			)
		),
        'UIInfo' => [
			'DisplayName' => [
				'en' => 'InAcademia admin portal (test)',
			],
			'Description' => [
				'en' => 'The portal for InAcademia administrators',
			],
			'InformationURL' => [
				'en' => 'https://inacademia.org',
			],
			'PrivacyStatementURL' => [
				'en' => 'https://inacademia.org/privacy-and-data-protection/',
			],
			'Keywords' => [
				'en' => ['inacademia', 'affiliation validation'],
			],
			'Logo' => [
				[
					'url'    => 'https://inacademia.org/wp-content/uploads/2017/02/inacademia_logo.jpg',
					'height' => 72,
					'width'  => 280,
					'lang'   => 'en',
				],
			],
		],
      
    ],
    
];
