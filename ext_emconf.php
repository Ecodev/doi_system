<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'OAI server to fetch data',
    'description' => 'OAI server to fetch data in a flexible way. Possible output format: JSON, Atom, HTML. The OAI server is meant for retrieving data only.',
    'category' => 'fe',
    'author' => 'Fabien Udriot',
    'author_email' => 'fabien@ecodev.ch',
    'state' => 'beta',
    'version' => '0.2.0-dev',
    'autoload' => [
        'psr-4' => ['Fab\\OaiServer\\' => 'Classes']
    ],
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '7.6.0-7.6.99',
                    'vidi' => '',
                ],
        ],
];
