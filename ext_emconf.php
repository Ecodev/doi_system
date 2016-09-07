<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'OAI server',
    'description' => 'OAI server emulation. OAI stands for Open Archives Initiative, a protocol for Metadata Harvesting.',
    'category' => 'fe',
    'author' => 'Fabien Udriot',
    'author_email' => 'fabien@ecodev.ch',
    'state' => 'beta',
    'version' => '0.1.0-dev',
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
