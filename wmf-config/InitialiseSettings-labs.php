<?php
# WARNING: This file is publically viewable on the web.
#          Do not put private data here.

/**
 * This file is for overriding the default InitialiseSettings.php with our own
 * stuff. Prefixing a setting key with '-' to override all values from
 * InitialiseSettings.php
 *
 * Please wrap your code in functions to avoid tainting the global namespace.
 */

/**
 * Main entry point to override production settings. Supports key beginning with
 * a dash to completely override a setting.
 * Settings are fetched through wmfLabsSettings() defined below.
 */
function wmfLabsOverrideSettings() {
	global $wmfConfigDir, $wgConf;

	// Override (or add) settings that we need within the labs environment,
	// but not in production.
	$betaSettings = wmfLabsSettings();

	// Set configuration string placeholder 'variant' to 'beta-hhvm'
	// or 'beta' depending on the the runtime executing the code.
	// This is to ensure that *.beta-hhvm.wmflabs.org wikis use
	// loginwiki.wikimedia.beta-hhvm.wmflabs.org as their loginwiki.
	$wgConf->siteParamsCallback = function( $conf, $wiki ) {
		$variant = 'beta';
		return array( 'params' => array( 'variant' => $variant ) );
	};

	foreach ( $betaSettings as $key => $value ) {
		if ( substr( $key, 0, 1 ) == '-' ) {
			// Settings prefixed with - are completely overriden
			$wgConf->settings[substr( $key, 1 )] = $value;
		} elseif ( isset( $wgConf->settings[$key] ) ) {
			$wgConf->settings[$key] = array_merge( $wgConf->settings[$key], $value );
		} else {
			$wgConf->settings[$key] = $value;
		}
	}
}

/**
 * Return settings for wmflabs cluster. This is used by wmfLabsOverride().
 * Keys that start with a hyphen will completely override the regular settings
 * in InitializeSettings.php. Keys that don't start with a hyphen will have
 * their settings combined with the regular settings.
 */
function wmfLabsSettings() {
	global $wmfUdp2logDest;
	return array(
		'wgParserCacheType' => array(
			'default' => CACHE_MEMCACHED,
		),

		'wgSitename' => array(
			'deploymentwiki' => 'Deployment',
			'ee_prototypewiki' => 'Editor Engagement Prototype',
			'wikivoyage'    => 'Wikivoyage',
		),

		'wgServer' => array(
			'default'     => '//$lang.wikipedia.$variant.wmflabs.org',
			'wiktionary'	=> '//$lang.wiktionary.$variant.wmflabs.org',
			'wikipedia'     => '//$lang.wikipedia.$variant.wmflabs.org',
			'wikiversity'	=> '//$lang.wikiversity.$variant.wmflabs.org',
			'wikispecies'	=> '//$lang.wikispecies.$variant.wmflabs.org',
			'wikisource'	=> '//$lang.wikisource.$variant.wmflabs.org',
			'wikiquote'	=> '//$lang.wikiquote.$variant.wmflabs.org',
			'wikinews'	=> '//$lang.wikinews.$variant.wmflabs.org',
			'wikibooks'     => '//$lang.wikibooks.$variant.wmflabs.org',
			'wikivoyage'    => '//$lang.wikivoyage.$variant.wmflabs.org',

			'commonswiki'   => '//commons.wikimedia.$variant.wmflabs.org',
			'deploymentwiki'      => '//deployment.wikimedia.$variant.wmflabs.org',
			'ee_prototypewiki' => '//ee-prototype.wikipedia.$variant.wmflabs.org',
			'loginwiki'     => '//login.wikimedia.$variant.wmflabs.org',
			'metawiki'      => '//meta.wikimedia.$variant.wmflabs.org',
			'testwiki'      => '//test.wikimedia.$variant.wmflabs.org',
			'zerowiki'      => '//zero.wikimedia.$variant.wmflabs.org',
			'wikidatawiki'  => '//wikidata.$variant.wmflabs.org',
		),

		'wgCanonicalServer' => array(
			'default'     => 'http://$lang.wikipedia.$variant.wmflabs.org',
			'wikipedia'     => 'http://$lang.wikipedia.$variant.wmflabs.org',
			'wikibooks'     => 'http://$lang.wikibooks.$variant.wmflabs.org',
			'wikiquote'	=> 'http://$lang.wikiquote.$variant.wmflabs.org',
			'wikinews'	=> 'http://$lang.wikinews.$variant.wmflabs.org',
			'wikisource'	=> 'http://$lang.wikisource.$variant.wmflabs.org',
			'wikiversity'     => 'http://$lang.wikiversity.$variant.wmflabs.org',
			'wiktionary'     => 'http://$lang.wiktionary.$variant.wmflabs.org',
			'wikispecies'     => 'http://$lang.wikispecies.$variant.wmflabs.org',
			'wikivoyage'    => 'http://$lang.wikivoyage.$variant.wmflabs.org',

			'metawiki'      => 'http://meta.wikimedia.$variant.wmflabs.org',
			'ee_prototypewiki' => 'http://ee-prototype.wikipedia.$variant.wmflabs.org',
			'commonswiki'	=> 'http://commons.wikimedia.$variant.wmflabs.org',
			'deploymentwiki'      => 'http://deployment.wikimedia.$variant.wmflabs.org',
			'loginwiki'     => 'http://login.wikimedia.$variant.wmflabs.org',
			'testwiki'      => 'http://test.wikimedia.$variant.wmflabs.org',
			'wikidatawiki'  => 'http://wikidata.$variant.wmflabs.org',
		),

		'wmgUsabilityPrefSwitch' => array(
			'default' => ''
		),

		'-wgUploadDirectory' => array(
			'default'      => '/data/project/upload7/$site/$lang',
			'private'      => '/data/project/upload7/private/$lang',
		),

		/* 'wmgUseOnlineStatusBar' => array( */
		/* 	'default' => false, */
		/* ), */

		'-wgUploadPath' => array(
			'default' => '//upload.$variant.wmflabs.org/$site/$lang',
			'private' => '/w/img_auth.php',
		//	'wikimania2005wiki' => '//upload..org/wikipedia/wikimania', // back compat
			'commonswiki' => '//upload.$variant.wmflabs.org/wikipedia/commons',
			'metawiki' => '//upload.$variant.wmflabs.org/wikipedia/meta',
			'testwiki' => '//upload.$variant.wmflabs.org/wikipedia/test',
		),

		'-wgThumbnailBuckets' => array(
			'default' => array( 256, 512, 1024, 2048, 4096 ),
		),

		'-wgThumbnailMinimumBucketDistance' => array(
			'default' => 32,
		),

		'-wmgMathPath' => array(
			'default' => '//upload.$variant.wmflabs.org/math',
		),

		'wmgNoticeProject' => array(
			'deploymentwiki' => 'meta',
		),

		'-wgDebugLogFile' => array(
			'default' => "udp://{$wmfUdp2logDest}/wfDebug",
		),

		'-wmgDefaultMonologHandler' => array(
			'default' => 'wgDebugLogFile',
		),

		'-wmgLogstashServers' => array(
			'default' => array(
				'10.68.16.134', // deployment-logstash1.eqiad.wmflabs
			),
		),

		// Additional log channels for beta cluster
		'wmgMonologChannels' => array(
			'default' => array(
				'CentralAuthVerbose' => 'debug',
				'dnsblacklist' => 'debug',
				'squid' => 'debug',
			),
		),

		//'-wgDebugLogGroups' => array(),
		'-wgRateLimitLog' => array(),
		'-wgJobLogFile' => array(),

		// Bug T62013, T58758
		'-wmgRC2UDPPrefix' => array(
			'default' => false,
		),

		'wmgUseWebFonts' => array(
			'mywiki' => true,
		),

		'wgLogo' => array(
			'commonswiki' => '$stdlogo',
			'dewiki' => '$stdlogo',
			'wikidatawiki' => '//upload.wikimedia.org/wikipedia/commons/thumb/4/43/Wikidata-logo-en-black.svg/135px-Wikidata-logo-en-black.svg.png',
		),

		'wgFavicon' => array(
			'dewiki' => '//upload.wikimedia.org/wikipedia/commons/1/14/Favicon-beta-wikipedia.png',
		),

		// Editor Engagement stuff
		'-wmfUseArticleCreationWorkflow' => array(
			'default' => false,
		),
		'wmgUseEcho' => array(
			'enwiki' => true,
			'en_rtlwiki' => true,
		),

		'-wmgUsePoolCounter' => array(
			'default' => false, # Bug T38891
		),
		'-wmgUseAPIRequestLog' => array(
			'default' => false,
		),
		'-wmgEnableCaptcha' => array(
			'default' => true,
		),
		'-wmgEchoCluster' => array(
			'default' => false,
		),
		'wmgEchoUseJobQueue' => array(
			'default' => true,
		),
		# FIXME: make that settings to be applied
		'-wgShowExceptionDetails' => array(
			'default' => true,
		),
		'-wgUseContributionTracking' => array(
			'default' => false,
		),
		'-wmgUseContributionReporting' => array(
			'default' => false,
		),

		# To help fight spam, makes rules maintained on deploymentwiki
		# to be available on all beta wikis.
		'-wmgAbuseFilterCentralDB' => array(
			'default' => 'deploymentwiki',
		),
		'-wmgUseGlobalAbuseFilters' => array(
			'default' => true,
		),

		# Bug T39852
		'wmgUseWikimediaShopLink' => array(
			'default'    => false,
			'enwiki'     => true,
			'simplewiki' => true,
		),

		//enable TimedMediaHandler and MwEmbedSupport for testing on commons and enwiki
		'wmgUseMwEmbedSupport' => array(
			'commonswiki'	=> true,
			'enwiki'	=> true,
		),
		// NOTE: TMH *requires* MwEmbedSupport to function
		'wmgUseTimedMediaHandler' => array(
			'commonswiki'	=> true,
			'enwiki'	=> true,
		),
		'wmgMobileUrlTemplate' => array(
			'default' => '%h0.m.%h1.%h2.%h3.%h4',
			'commonswiki' => '',
			'mediawikiwiki' => '',//'m.%h1.%h2',
			'wikidatawiki' => 'm.%h0.%h1.%h2.%h3', // T87440
		),

		'wmgMFPhotoUploadEndpoint' => array(
			'default' => '//commons.wikimedia.$variant.wmflabs.org/w/api.php',
		),
		'wmgMFUseCentralAuthToken' => array(
			'default' => true,
		),
		'wmgMFEnableBetaDiff' => array(
			'default' => true,
		),
		'wmgMFSpecialCaseMainPage' => array(
			'default' => true,
			'enwiki' => false,
		),
		'wmgWikiGrokUIEnable' => array(
			'default' => false,
			'enwiki' => true, // prototype version is for en.wiki only
		),
		'wmgWikiGrokUIEnableOnAllDevices' => array(
			'default' => false,
			'enwiki' => true,
		),
		'wmgWikiGrokUIEnableInSidebar' => array(
			'default' => false,
			'enwiki' => true,
		),
		'wmgMFWikiDataEndpoint' => array(
			'default' => 'http://wikidata.beta.wmflabs.org/w/api.php',
		),
		'wmgWikiBasePropertyConfig' => array(
			'default' => array(
				'instanceOf' => 'P694',
				'bannerImage' => 'P964',
			),
		),
		'wmgMFInfoboxConfig' => array(
			'default' => array(
				// human
				44076 => array(
					'rows' => array(
							// Born
							array( 'id' => 'P476' ),
							// Birthplace
							array( 'id' => 'P965' ),
							// Place of death
							array( 'id' => 'P994' ),
							// Country of citizenship
							array( 'id' => 'P27' ),
							// Alma mater
							array( 'id' => 'P998' ),
					),
				),
				'default' => array(
					'rows' => array(
					),
				),
			),
		),

		'wmgWikiGrokDebug' => array(
			'default' => true,
		),

		'wmgMFIsBrowseEnabled' => array(
			'default' => true,
		),
		'wmgMFBrowseTags' => array(
			'default' => array(
				'Category:National_Basketball_Association_All-Stars' => 'NBA All Stars',
				'Category:20th-century_American_politicians' => 'American politicians',
				'Category:Object-oriented_programming_languages' => 'object-oriented programming languages',
				'Category:Western_Europe' => 'European states',
				'Category:American_female_pop_singers' => 'American female pop singers',
				'Category:American_drama_television_series' => 'American drama TV series',
				'Category:Modern_painters' => 'modern painters',
				'Category:Landmarks_in_San_Francisco,_California' => 'landmarks in San Francisco, California',
			)
		),

		'wmgGeoDataDebug' => array(
			'default' => true,
		),

		'wmgULSPosition' => array(
			# Beta-specific
			'ee-prototype' => 'personal',
			'deploymentwiki' => 'personal',
		),

		// (Bug T41653) The plan is to enable it for testing on labs first, so add
		// the config hook to be able to do that.
		'wmgUseCodeEditorForCore' => array(
			'default' => true,
		),

		'wmgUseCommonsMetadata' => array(
			'default' => true,
		),
		'wmgCommonsMetadataForceRecalculate' => array(
			'default' => true,
		),

		'wmgUseGWToolset' => array(
			'default' => false,
			'commonswiki' => true,
		),

		// Don't use an http/https proxy
		'-wgCopyUploadProxy' => array(
			'default' => false,
		),

		// ----------- BetaFeatures start ----------
		'wmgUseBetaFeatures' => array(
			'default' => true,
		),

		// Enable all Beta Features in Beta Labs, even if not in production whitelist
		'wmgBetaFeaturesWhitelist' => array(
			'default' => false,
		),

		'wmgUseMultimediaViewer' => array(
			'default' => true,
		),

		'wmgNetworkPerformanceSamplingFactor' => array(
			'default' => 1,
		),

		'wmgUseImageMetrics' => array(
			'default' => true,
		),

		'wmgImageMetricsSamplingFactor' => array(
			'default' => 1,
		),

		'wmgImageMetricsCorsSamplingFactor' => array(
			'default' => 1,
		),

		'wmgRestbaseServer' => array(
			'default' => "http://10.68.17.227:7231" // deployment-restbase01.eqiad.wmflabs
		),

		'wmgUseRestbaseVRS' => array(
			'default' => true,
		),

		'wmgUseVectorBeta' => array(
			'default' => true,
		),
		'wmgVectorBetaFormRefresh' => array(
			'default' => true,
		),

		'wmgVectorBetaPersonalBar' => array(
			'default' => true,
		),

		'wmgVectorBetaWinter' => array(
			'default' => true,
		),

		'wmgVisualEditorExperimental' => array(
			'default' => true,
		),

		'wmgVisualEditorEnableTocWidget' => array(
			'default' => false,
		),
		'wmgVisualEditorAccessRESTbaseDirectly' => array(
			'default' => true,
		),
		// ------------ BetaFeatures end -----------

		'wmgUseRSSExtension' => array(
			'dewiki' => true,
		),

		'wmgRSSUrlWhitelist' => array(
			'dewiki' => array( 'http://de.planet.wikimedia.org/atom.xml' ),
		),

		'wmgUseCampaigns' => array(
			'default' => true,
		),

		'wmgUseEventLogging' => array(
			'default' => true,
		),

		'wmgContentTranslationCluster' => array(
			'default' => false,
		),

		'wmgContentTranslationCampaigns' => array(
			'default' => array( 'newarticle' ),
		),

		'wmgUseNavigationTiming' => array(
			'default' => true,
		),

		'wgSecureLogin' => array(
			// Setting false throughout Labs for now due to untrusted SSL certificate
			// Bug T50501
			'default' => false,
			'loginwiki' => false,
		),

		'wgSearchSuggestCacheExpiry' => array(
			'default' => 300,
		),

		'wmgUseCirrus' => array(
			'default' => true,
			'commonswiki' => true,
			'dewiki' => true,
			'enwiki' => true,
			'eswiki' => true,
			'frwiki' => true,
			'jawiki' => true,
			'nlwiki' => true,
			'plwiki' => true,
			'ruwiki' => true,
			'svwiki' => true,
			'zhwiki' => true,
		),

		'wmgUseFlow' => array(
			'enwiki' => true,
			'en_rtlwiki' => true,
		),
		# Extension:Flow's browsertests use Talk:Flow_QA.
		'wmgFlowOccupyPages' => array(
			'enwiki' => array( 'Talk:Flow QA', 'Talk:Flow' ),
			'en_rtlwiki' => array( 'Talk:Flow' ),
		),
		# No separate Flow DB or cluster (yet) for labs.
		'-wmgFlowDefaultWikiDb' => array(
			'default' => false,
		),
		'-wmgFlowCluster' => array(
			'default' => false,
		),
		'wmgUseGather' => array(
			'default' => true,
		),
		'wmgUseGuidedTour' => array(
			'wikidatawiki' => true,
		),
		// Enable anonymous editor acquisition experiment across labs
		'wmgGettingStartedRunTest' => array(
			'default' => true,
		),
		'+wmgExtraLanguageNames' => array(
			'default' => array(),
			'en_rtlwiki' => array( 'en-rtl' => 'English (rtl)' ),
		),
		'wmgUseContentTranslation' => array(
			'default' => false,
			'wiki' => true,
		),

		'wmgUsePetition' => array(
			'default' => false,
			'metawiki' => true,
		),

		'wmgUseSentry' => array(
			'default' => true,
		),
		'wmgSentryDsn' => array(
			'default' => '//c357be0613e24340a96aeaa28dde08ad@sentry-beta.wmflabs.org/4',
		),

		// Thumbnail prerendering at upload time
		'wgUploadThumbnailRenderMap' => array(
			'default' => array( 320, 640, 800, 1024, 1280, 1920, 2560, 2880 ),
		),

		'wgUploadThumbnailRenderMethod' => array(
			'default' => 'http',
		),

		'wgUploadThumbnailRenderHttpCustomHost' => array(
			'default' => 'upload.beta.wmflabs.org',
		),

		'wgUploadThumbnailRenderHttpCustomDomain' => array(
			'default' => 'deployment-cache-upload02.eqiad.wmflabs',
		),

		'wmgUseApiFeatureUsage' => array(
			'default' => true,
		),

		'wmgUseBounceHandler' => array(
			'default' => true,
		),

		'-wmgScorePath' => array(
			'default' => "//upload.beta.wmflabs.org/score",
		),

		'wgRateLimitsExcludedIPs' => array(
			'default' => array( '198.73.209.0/24' ), // T87841 Office IP
		),

		'wmgUseCapiunto' => array(
			'default' => true,
		),

		'wmgUsePopups' => array(
			'default' => true,
		),

		'wmgUseJosa' => array(
			'default' => false,
			'kowiki' => true, // T15712
		),
		'wmgUseGraph' => array(
			'default' => true,
			'wikidatawiki' => true,
		),
		'wmgGraphImgServiceAlways' => array(
			'default' => true,
		),
	);
} # wmflLabsSettings()
