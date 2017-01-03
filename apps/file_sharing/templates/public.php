<?php
	$file_ext = strtolower(substr($_['filename'], strrpos($_['filename'], '.') + 1));

	$image_types = [
		'png' => true,
		'jpg' => true,
		'jpeg' => true,
		'gif' => true,
	];

	$video_types = [
		'webm' => true,
		'mp4' => true,
		'mp3' => true,
		'flv' => true,
	];

	$is_image = isset($image_types[$file_ext]);
	$is_video = isset($video_types[$file_ext]);

	//print_r($_);
	//exit;

	header('Content-Security-Policy:');
?>
<?php if ($is_image or $is_video): ?>

<head>
	<title><?php echo $_['filename']; ?></title>
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
		jwplayer.key="BHdWH5ZmchzVO9kSC+idtQo0p0Gf9jHCCPmg3w=="

		jwplayer("video_player").setup({
			title: "<?php echo $_['filename'] ?>",
		    file: "<?php echo $_['downloadURL']; ?>",
		    type: "<?php echo $file_ext; ?>",
		    image: "<?php echo("/apps/files_sharing/img/" . (($file_ext == 'mp3')  ? 'mp3' : 'mov') . ".png"); ?>",
		    preload: true,
		    abouttext: "Download",
		    aboutlink: "<?php echo $_['downloadURL']; ?>",
		});
	</script>
<?php exit; endif; ?>


<?php
	$url = $_['downloadURL'];
	echo "<img id='img' style='max-width: 100%; max-height: 100%;' src='$url'>";

	/*	header('Content-Type: ' . $_['mimetype']);
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $_['downloadURL']);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$data = curl_exec($ch);
		curl_close($ch);
		echo $data;*/
		
	//}
		
	//\OCP\Util::addScript('files_sharing', 'video');
	//\OCP\Util::addStyle('files_sharing', 'video');
	//\OCP\Util::addStyle('files_sharing', 'video_sublime');
?>

<?php exit; endif; ?>

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


<header>
	<div id="header" class="<?php p((isset($_['folder']) ? 'share-folder' : 'share-file')) ?>">
		<a href="<?php print_unescaped(link_to('', 'index.php')); ?>"
		   title="" id="nextcloud">
			<div class="logo-icon svg">
			</div>
		</a>

		<div class="header-appname-container">
			<h1 class="header-appname">
				<?php p($theme->getName()); ?>
			</h1>
		</div>

		<div id="logo-claim" style="display:none;"><?php p($theme->getLogoClaim()); ?></div>
		<div class="header-right">
			<span id="details">
				<?php
				if (!isset($_['hideFileList']) || (isset($_['hideFileList']) && $_['hideFileList'] === false)) {
					if ($_['server2serversharing']) {
						?>
						<span id="save" data-protected="<?php p($_['protected']) ?>"
							  data-owner-display-name="<?php p($_['displayName']) ?>" data-owner="<?php p($_['owner']) ?>" data-name="<?php p($_['filename']) ?>">
						<button id="save-button"><?php p($l->t('Add to your Nextcloud')) ?></button>
						<form class="save-form hidden" action="#">
							<input type="text" id="remote_address" placeholder="user@yourNextcloud.org"/>
							<button id="save-button-confirm" class="icon-confirm svg" disabled></button>
						</form>
					</span>
					<?php } ?>
					<a href="<?php p($_['downloadURL']); ?>" id="download" class="button">
						<img class="svg" alt="" src="<?php print_unescaped(image_path("core", "actions/download.svg")); ?>"/>
						<span id="download-text"><?php p($l->t('Download'))?></span>
					</a>
				<?php } ?>
			</span>
		</div>
	</div>
</header>

<div id="content-wrapper-share">
	<?php if (!isset($_['hideFileList']) || (isset($_['hideFileList']) && $_['hideFileList'] === false)) { ?>
	<div id="preview">
			<?php if (isset($_['folder'])): ?>
				<?php print_unescaped($_['folder']); ?>
			<?php else: ?>
				<?php if ($_['previewEnabled'] && substr($_['mimetype'], 0, strpos($_['mimetype'], '/')) == 'video'): ?>
					<div id="imgframe">
	
						<video id="home_video" class="video-js vjs-sublime-skin" controls preload="auto" data-setup='{"techOrder": ["html5"]}'>
							<source src="<?php echo $_['downloadURL'] ?>" type="<?php echo $_['mimetype'] ?>"></source>
						</video>
			
					</div>
				<?php else: ?>
					<!-- Preview frame is filled via JS to support SVG images for modern browsers -->
					<div id="imgframe"></div>
				<?php endif; ?>
				<div class="directDownload">
					<a href="<?php p($_['downloadURL']); ?>" id="downloadFile" class="button">
						<img class="svg" alt="" src="<?php print_unescaped(image_path("core", "actions/download.svg")); ?>"/>
						<?php p($l->t('Download %s', array($_['filename'])))?> (<?php p($_['fileSize']) ?>)
					</a>
				</div>
			<?php endif; ?>
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
		data-url="<?php print_unescaped(OCP\Util::linkTo('files', 'ajax/upload.php')); ?>" />
	</div>
	<?php endif; ?>
	<footer>
		<p class="info">
			<?php print_unescaped($theme->getLongFooter()); ?>
		</p>
	</footer>
</div>
