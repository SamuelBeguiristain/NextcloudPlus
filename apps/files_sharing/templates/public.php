<?php
	$file_ext = strtolower(substr($_['filename'], strrpos($_['filename'], '.') + 1));

	$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	//$fileUrl = $url . ($_['mimetype'] == "image/gif" ? "/download" : "/preview");
	$fileUrl = $url . "/download";

	$image_types = [
		'png' => true,
		'jpg' => true,
		'jpeg' => true,
		'gif' => true,
		'svg' => true
	];

	$video_types = [
		'webm' => true,
		'mp4' => true,
		'mp3' => true,
		'flv' => true,
		'ogg' => true
	];

	$text_types = [
		'html' => 'html',
		'php' => 'php',
		'lua' => 'lua',
		'cs' => 'csharp',
		'tpl' => 'smarty',
		'txt' => 'auto',
		'sh' => 'bash'
	];

	$is_image = isset($image_types[$file_ext]);
	$is_video = isset($video_types[$file_ext]);
	$is_text = isset($text_types[$file_ext]);

	header('Content-Security-Policy:');
?>

<?php /*print_r($_); exit;*/ if ($is_image or $is_video or $is_text): ?>

	<head>
		<title><?php echo $_['filename']; ?></title>

		<?php if ($is_text): ?>
			<link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.12.0/build/styles/tomorrow-night.min.css"">
		<?php endif; ?>

		<meta property="og:site_name" content="<?php echo $_SERVER['HTTP_HOST']; ?>">
		<meta property="og:title" content="<?php echo $_['filename']; ?>"/>
		<meta property="og:url" content="<?php echo $url; ?>"/>

		<?php if ($is_image): ?>
			<meta property="twitter:card" content="photo"/>
			<meta property="og:image" content="<?php echo $fileUrl ?>"/>
			<meta property="og:image:type" content="<?php echo $_['mimetype']; ?>" />

		<?php elseif ($is_video): ?>
			<meta property="og:type" content="video.other"/>
			<meta property="og:image" content="/apps/files_sharing/img/mov.png"/>

			<meta property="og:video" content="<?php echo $_['downloadURL']; ?>"/>
			<meta property="og:video:type" content="<?php echo $_['mimetype']; ?>">
			<meta property="og:video:width" content="<?php echo $_['previewMaxX']; ?>" />
			<meta property="og:video:height" content="<?php echo $_['previewMaxY']; ?>" />
		<?php endif; ?>
	</head>

	<!-- Stolen from firefox dev theme -->
	<style type="text/css">
		@media not print {
		  .overflowingVertical, .overflowingHorizontalOnly {
			cursor: zoom-out;
		  }

		  .shrinkToFit {
			cursor: zoom-in;
		  }
		}
		@media print {
			img {
				display: block;
			}
		}
		@media not print {
		body {
			margin: 0;
		}
		img {
			text-align: center;
			position: absolute;
			margin: auto;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			object-fit: contain;
		  }

		  img.overflowingVertical {
			margin-top: 0;
		  }

			.completeRotation {
				transition: transform 0.3s ease 0s;
			}
		}
		img {
			image-orientation: from-image;
		}
		@media not print {
			body {
				color: #eee;
				background-image: url("/apps/files_sharing/img/imagedoc-darknoise.png");
			}

			img.transparent {
				color: #222;
			}
		}
	</style>


	<?php if ($is_video): ?>
		<script type="text/javascript" src="https://content.jwplatform.com/libraries/JGrz7Tt8.js"></script>

		<div style="width: 70%; margin: 0 auto; padding-top: 5% ">
			<div id="video_player"></div>
		</div>

		<script type="text/JavaScript">
			jwplayer("video_player").setup({
				title: "<?php echo $_['filename'] ?>",
				file: "<?php echo $_['downloadURL']; ?>",
				type: "<?php echo $file_ext; ?>",
				image: "<?php echo("/apps/files_sharing/img/" . (($file_ext == 'mp3') ? 'mp3' : 'mov') . ".png"); ?>",
				preload: true,
				abouttext: "Download",
				aboutlink: "<?php echo $_['downloadURL']; ?>",
			});
		</script>
	<?php endif; ?>


	<?php if ($is_image): ?>
		<img id='img' style='max-width: 100%; max-height: 100%;' src='<?php echo $fileUrl ?>'>
	<?php endif; ?>


	<?php if ($is_text): ?>
		<style type="text/css">
			code[class*="language-"],
			pre[class*="language-"] {
				font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
				line-height: 1.25;
				font-size: 12px;
			}
			#code {
				margin-left: -115px;
			}

			.loader {
				margin: 100px auto;
				font-size: 25px;
				width: 1em;
				height: 1em;
				border-radius: 50%;
				position: relative;
				text-indent: -9999em;
				-webkit-animation: load5 1.1s infinite ease;
				animation: load5 1.1s infinite ease;
				-webkit-transform: translateZ(0);
				-ms-transform: translateZ(0);
				transform: translateZ(0);
				}
				@-webkit-keyframes load5 {
				0%,
				100% {
					box-shadow: 0em -2.6em 0em 0em #ffffff, 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.5), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.7);
				}
				12.5% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.7), 1.8em -1.8em 0 0em #ffffff, 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.5);
				}
				25% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.5), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.7), 2.5em 0em 0 0em #ffffff, 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				37.5% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.5), 2.5em 0em 0 0em rgba(255, 255, 255, 0.7), 1.75em 1.75em 0 0em #ffffff, 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				50% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.5), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.7), 0em 2.5em 0 0em #ffffff, -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				62.5% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.5), 0em 2.5em 0 0em rgba(255, 255, 255, 0.7), -1.8em 1.8em 0 0em #ffffff, -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				75% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.5), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.7), -2.6em 0em 0 0em #ffffff, -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				87.5% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.5), -2.6em 0em 0 0em rgba(255, 255, 255, 0.7), -1.8em -1.8em 0 0em #ffffff;
				}
				}
				@keyframes load5 {
				0%,
				100% {
					box-shadow: 0em -2.6em 0em 0em #ffffff, 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.5), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.7);
				}
				12.5% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.7), 1.8em -1.8em 0 0em #ffffff, 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.5);
				}
				25% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.5), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.7), 2.5em 0em 0 0em #ffffff, 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				37.5% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.5), 2.5em 0em 0 0em rgba(255, 255, 255, 0.7), 1.75em 1.75em 0 0em #ffffff, 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				50% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.5), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.7), 0em 2.5em 0 0em #ffffff, -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.2), -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				62.5% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.5), 0em 2.5em 0 0em rgba(255, 255, 255, 0.7), -1.8em 1.8em 0 0em #ffffff, -2.6em 0em 0 0em rgba(255, 255, 255, 0.2), -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				75% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.5), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.7), -2.6em 0em 0 0em #ffffff, -1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2);
				}
				87.5% {
					box-shadow: 0em -2.6em 0em 0em rgba(255, 255, 255, 0.2), 1.8em -1.8em 0 0em rgba(255, 255, 255, 0.2), 2.5em 0em 0 0em rgba(255, 255, 255, 0.2), 1.75em 1.75em 0 0em rgba(255, 255, 255, 0.2), 0em 2.5em 0 0em rgba(255, 255, 255, 0.2), -1.8em 1.8em 0 0em rgba(255, 255, 255, 0.5), -2.6em 0em 0 0em rgba(255, 255, 255, 0.7), -1.8em -1.8em 0 0em #ffffff;
				}
			}
		</style>

		<div style="padding-left: 10%; padding-top: 25px; padding-bottom: 25px; width: 80%;">
			<pre>
				<code id="code">
				<div class="loader">Loading...</div>
				</code>
			</pre>
		</div>

		<script type="text/javascript" src="/apps/files_sharing/js/highlight.pack.js"></script>

		<script type="text/javascript">
			function htmlEntities(str) {
				return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').trim();
			}
			var xhr = new XMLHttpRequest();
			xhr.open('GET', '<?php echo $_["downloadURL"] ?>');
			xhr.onload = function() {
				document.getElementById("code").innerHTML = htmlEntities(xhr.responseText);
				hljs.initHighlighting();
			};
			xhr.send();
		</script>
	<?php endif; ?>

<?php exit; endif; ?>



<!-- Stock page -->
<?php
/** @var $l \OCP\IL10N */
/** @var $_ array */
?>

<?php if ($_['previewSupported']): /* This enables preview images for links (e.g. on Facebook, Google+, ...)*/?>
	<link rel="image_src" href="<?php p($_['previewImage']); ?>" />
<?php endif; ?>

<div id="notification-container">
	<div id="notification" style="display: none;"></div>
</div>

<input type="hidden" id="sharingUserId" value="<?php p($_['owner']) ?>">
<input type="hidden" id="filesApp" name="filesApp" value="1">
<input type="hidden" id="isPublic" name="isPublic" value="1">
<input type="hidden" name="dir" value="<?php p($_['dir']) ?>" id="dir">
<input type="hidden" name="downloadURL" value="<?php p($_['downloadURL']) ?>" id="downloadURL">
<input type="hidden" name="previewURL" value="<?php p($_['previewURL']) ?>" id="previewURL">
<input type="hidden" name="sharingToken" value="<?php p($_['sharingToken']) ?>" id="sharingToken">
<input type="hidden" name="filename" value="<?php p($_['filename']) ?>" id="filename">
<input type="hidden" name="mimetype" value="<?php p($_['mimetype']) ?>" id="mimetype">
<input type="hidden" name="previewSupported" value="<?php p($_['previewSupported'] ? 'true' : 'false'); ?>" id="previewSupported">
<input type="hidden" name="mimetypeIcon" value="<?php p(\OC::$server->getMimeTypeDetector()->mimeTypeIcon($_['mimetype'])); ?>" id="mimetypeIcon">
<?php
$upload_max_filesize = OC::$server->getIniWrapper()->getBytes('upload_max_filesize');
$post_max_size = OC::$server->getIniWrapper()->getBytes('post_max_size');
$maxUploadFilesize = min($upload_max_filesize, $post_max_size);
?>
<input type="hidden" name="maxFilesizeUpload" value="<?php p($maxUploadFilesize); ?>" id="maxFilesizeUpload">

<?php if (!isset($_['hideFileList']) || (isset($_['hideFileList']) && $_['hideFileList'] === false)): ?>
	<input type="hidden" name="filesize" value="<?php p($_['nonHumanFileSize']); ?>" id="filesize">
<?php endif; ?>
<input type="hidden" name="maxSizeAnimateGif" value="<?php p($_['maxSizeAnimateGif']); ?>" id="maxSizeAnimateGif">


<header><div id="header" class="<?php p((isset($_['folder']) ? 'share-folder' : 'share-file')) ?>">
		<div class="header-left">
			<span id="nextcloud">
				<div class="logo logo-icon svg"></div>
				<h1 class="header-appname">
					<?php p($_['filename']); ?>
				</h1>
				<div class="header-shared-by">
					<?php echo p($l->t('shared by %s', [$_['displayName']])); ?>
				</div>
			</span>
		</div>

		<div class="header-right">
			<?php if (!isset($_['hideFileList']) || (isset($_['hideFileList']) && $_['hideFileList'] === false)) { ?>
			<a id="share-menutoggle" class="menutoggle icon-more-white"><span class="share-menutoggle-text"><?php p($l->t('Download')) ?></span></a>
			<div id="share-menu" class="popovermenu menu">
				<ul>
					<li>
						<a href="<?php p($_['downloadURL']); ?>" id="download">
							<span class="icon icon-download"></span>
							<?php p($l->t('Download'))?>&nbsp;<span class="download-size">(<?php p($_['fileSize']) ?>)</span>
						</a>
					</li>
					<li>
						<a id="directLink-container">
							<span class="icon icon-public"></span>
							<label for="directLink"><?php p($l->t('Direct link')) ?></label>
							<input id="directLink" type="text" readonly value="<?php p($_['previewURL']); ?>">
						</a>
					</li>
					<?php if ($_['server2serversharing']) { ?>
					<li>
						<a id="save" data-protected="<?php p($_['protected']) ?>"
							  data-owner-display-name="<?php p($_['displayName']) ?>" data-owner="<?php p($_['owner']) ?>" data-name="<?php p($_['filename']) ?>">
							<span class="icon icon-external"></span>
							<span id="save-button"><?php p($l->t('Add to your Nextcloud')) ?></span>
							<form class="save-form hidden" action="#">
								<input type="text" id="remote_address" placeholder="user@yourNextcloud.org"/>
								<button id="save-button-confirm" class="icon-confirm svg" disabled></button>
							</form>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div></header>
<div id="content-wrapper">
	<?php if (!isset($_['hideFileList']) || (isset($_['hideFileList']) && $_['hideFileList'] === false)) { ?>
	<div id="content">
	<div id="preview">
			<?php if (isset($_['folder'])): ?>
				<?php print_unescaped($_['folder']); ?>
			<?php else: ?>
				<?php if ($_['previewEnabled'] && substr($_['mimetype'], 0, strpos($_['mimetype'], '/')) === 'video'): ?>
					<div id="imgframe">
						<video tabindex="0" controls="" preload="none" style="max-width: <?php p($_['previewMaxX']); ?>px; max-height: <?php p($_['previewMaxY']); ?>px">
							<source src="<?php p($_['downloadURL']); ?>" type="<?php p($_['mimetype']); ?>" />
						</video>
					</div>
				<?php elseif ($_['previewEnabled'] && substr($_['mimetype'], 0, strpos($_['mimetype'], '/')) == 'audio'): ?>
					<div id="imgframe">
						<audio tabindex="0" controls="" preload="none" style="width: 100%; max-width: <?php p($_['previewMaxX']); ?>px; max-height: <?php p($_['previewMaxY']); ?>px">
							<source src="<?php p($_['downloadURL']); ?>" type="<?php p($_['mimetype']); ?>" />
						</audio>
					</div>
				<?php else: ?>
					<!-- Preview frame is filled via JS to support SVG images for modern browsers -->
					<div id="imgframe"></div>
				<?php endif; ?>
				<?php if ($_['previewURL'] === $_['downloadURL']): ?>
				<div class="directDownload">
					<a href="<?php p($_['downloadURL']); ?>" id="downloadFile" class="button">
						<span class="icon icon-download"></span>
						<?php p($l->t('Download %s', array($_['filename'])))?> (<?php p($_['fileSize']) ?>)
					</a>
				</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		</div>
		<?php } else { ?>
		<input type="hidden" id="upload-only-interface" value="1"/>
			<div id="public-upload">
				<div id="emptycontent" class="<?php if (!empty($_['disclaimer'])) { ?>has-disclaimer<?php } ?>">
					<div id="displayavatar"><div class="avatardiv"></div></div>
					<h2><?php p($l->t('Upload files to %s', [$_['shareOwner']])) ?></h2>
					<p><span class="icon-folder"></span> <?php p($_['filename']) ?></p>
					<?php if (!empty($_['disclaimer'])) { ?>
					<p class="disclaimer"><?php p($_['disclaimer']); ?></p>
					<?php } ?>
					<input type="file" name="files[]" class="hidden" multiple>

					<a href="#" class="button icon-upload"><?php p($l->t('Select or drop files')) ?></a>
					<div id="drop-upload-progress-indicator" style="padding-top: 25px;" class="hidden"><?php p($l->t('Uploading files…')) ?></div>
					<div id="drop-upload-done-indicator" style="padding-top: 25px;" class="hidden"><?php p($l->t('Uploaded files:')) ?></div>
					<ul>
					</ul>
				</div>
			</div>
		<?php } ?>
<?php if (!isset($_['hideFileList']) || (isset($_['hideFileList']) && $_['hideFileList'] !== true)): ?>
	<input type="hidden" name="dir" id="dir" value="" />
	<div class="hiddenuploadfield">
	<input type="file" id="file_upload_start" class="hiddenuploadfield" name="files[]"
		data-url="<?php p(OCP\Util::linkTo('files', 'ajax/upload.php')); ?>" />
	</div>
	<?php endif; ?>
	<footer>
		<p class="info">
			<?php print_unescaped($theme->getLongFooter()); ?>
		</p>
	</footer>
</div>
