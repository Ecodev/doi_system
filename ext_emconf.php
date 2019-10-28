<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'DOI System',
    'description' => 'DOI System - OAI server emulation. OAI stands for Open Archives Initiative, a protocol for Metadata Harvesting.',
    'category' => 'fe',
    'author' => 'Fabien Udriot',
    'author_email' => 'fabien@ecodev.ch',
    'state' => 'beta',
    'version' => '0.3.0-dev',
    'autoload' => [
        'psr-4' => ['Fab\\DoiSystem\\' => 'Classes']
    ],
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '7.6.0-9.5.99',
                    'vidi' => '0.0.0-0.0.0',
                ],
        ],
];
