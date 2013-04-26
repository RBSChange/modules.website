<?php
// "$this" is an instance of website_Page
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n" ?>
<?php echo $this->getDoctype(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:change="http://www.rbs.fr/change/1.0/schema" xml:lang="<?php echo $this->getLang(); ?>" lang="<?php echo $this->getLang(); ?>">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo htmlspecialchars($this->getTitle(), ENT_COMPAT, "utf-8"); ?></title>
		<meta http-equiv="content-language" content="<?php echo $this->getLang(); ?>" />
		<meta name="language" content="<?php echo $this->getLang(); ?>" />
		<meta name="description" <?php echo f_util_HtmlUtils::buildAttribute("content", $this->getDescription()); ?> />
		<meta name="keywords" <?php echo f_util_HtmlUtils::buildAttribute("content", $this->getKeywords()); ?> />
		<meta name="generator" content="RBS Change <?php echo Framework::getVersion(); ?>" />
		<?php echo $this->getMetas(); ?>
        <?php echo $this->getStylesheetInclusions(); ?>
		<?php echo $this->getStyles(); ?>
        <?php echo $this->getLinkTags(); ?>
        <script type="text/javascript">
			var pageHandler = <?php echo $this->getJSONHandler(); ?>;
	        function initialize()
	        {
				updateOrientation();
				document.body.addEventListener('orientationchange', updateOrientation, false);
			}
			function updateOrientation()
			{
				if (window.orientation == -90 || window.orientation == 90) document.body.setAttribute("orientation", "landscape");
				else document.body.setAttribute("orientation", "portrait");
				hideURL();
			}
			function hideURL(doIt)
			{
				if (doIt) window.scrollTo(0, 1);
				else window.setTimeout(hideURL, 100, true);
			}
			window.onload = initialize;
        </script>
        <?php echo $this->getScripts(); ?>
        <?php echo $this->getPlainHeadMarker(); ?>
	</head>
	<?php echo $this->htmlBody ?>
</html>
