DOI System for TYPO3 CMS
========================

This is a TYPO3 CMS extension emulating an OAI server. OAI stands for Open Archives Initiative, a protocol for Metadata Harvesting.

The URLs given as example are encoded.

	# Return all document
	http://domain.tld/doi/


Under the hood, the URL is decoded and corresponds to something like::

	http://domain.tld/index.php?eID=doi_system&route=users/1;


Configuration
=============

The configuration is done via TypoScript.


Installation
============


1. Install via composer or clone the extension into /path/typo3conf/ext/.

    $ composer require fab/doi-system

2. Go to Extension Manager and activate the extension doi_system.
3. Add a rewrite rule to your .htaccess:

    RewriteRule ^doi/(.*)$ /index.php?eID=doi_system [QSA,L]

or, if you are using Nginx:

    rewrite ^/doi/(.*)$ /index.php?eID=doi_system last;

Now you can start fetching content with ``doi/``.