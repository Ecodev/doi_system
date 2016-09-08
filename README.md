OAI server for TYPO3 CMS
=========================

This is a TYPO3 CMS extension emulating an OAI server. OAI stands for Open Archives Initiative, a protocol for Metadata Harvesting.

The URLs given as example are encoded.

	# Return all document
	http://domain.tld/oai/


Under the hood, the URL is decoded and corresponds to something like::

	http://domain.tld/index.php?eID=oai_server&route=users/1;


Configuration
=============

The configuration is done via TypoScript.


Installation
============


1. Install via composer or clone the extension into /path/typo3conf/ext/.

    $ composer require fab/oai-server

2. Go to Extension Manager and activate the extension oai_server.
3. Add a rewrite rule to your .htaccess:

    RewriteRule ^oai/(.*)$ /index.php?eID=oai_server&route=$1 [QSA,L]

or, if you are using Nginx:

    rewrite ^/oai/(.*)$ /index.php?eID=oai_server&route=$1 last;

Now you can start fetching content with ``oai/``.