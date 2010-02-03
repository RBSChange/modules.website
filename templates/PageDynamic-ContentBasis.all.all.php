<?php
// "$this" is an instance of website_Page
 echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:change="http://www.rbs.fr/change/1.0/schema" xml:lang="<?php echo $this->getLang(); ?>">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo htmlspecialchars($this->getTitle(), ENT_COMPAT, "utf-8"); ?></title>
		<meta http-equiv="content-language" content="<?php echo $this->getLang(); ?>" />
		<meta name="language" content="<?php echo $this->getLang(); ?>" />
		<meta name="description" <?php echo f_util_HtmlUtils::buildAttribute("content", $this->getDescription()); ?> />
		<meta name="keywords" <?php echo f_util_HtmlUtils::buildAttribute("content", $this->getKeywords()); ?> />
		<?php echo $this->getMetas(); ?>
		<?php echo $this->getStylesheetInclusions(); ?>
		<?php echo $this->getStyles(); ?>
		<?php echo $this->getLinkTags(); ?>
        <script type="text/javascript">var pageHandler = <?php echo $this->getJSONHandler(); ?></script>
        <?php echo $this->getScripts(); ?>
	</head>
    <?php 
    echo $this->htmlBody;
	$this->renderBenchTimes();
    ?>
</html>
