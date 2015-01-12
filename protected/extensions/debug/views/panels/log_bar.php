<?php
/* @var Yii2LogPanel $this */
/* @var int $count */
/* @var int $errorCount */
/* @var int $warningCount */
/* @var int $infoCount */

$title = "Logged $count messages";
if ($errorCount) $title .= " $errorCount errors";
if ($warningCount) $title .= " $warningCount warnings";
if ($infoCount) $title .= " $infoCount info";
?>
<div class="yii2-debug-toolbar-block">
	<a href="<?= $this->getUrl() ?>" title="<?= $title ?>">
		Log <span class="label"><?= $count ?></span>
	</a>
	<?php if ($errorCount): ?>
		<a href="<?= $this->getUrl() ?>#first-error" title="<?= $title ?>">
			<span class="label label-important"><?= $errorCount ?></span>
		</a>
	<?php endif; ?>
	<?php if ($warningCount): ?>
		<a href="<?= $this->getUrl() ?>#first-warning" title="<?= $title ?>">
			<span class="label label-warning"><?= $warningCount ?></span>
		</a>
	<?php endif; ?>
	<?php if ($infoCount): ?>
		<a href="<?= $this->getUrl() ?>#first-info" title="<?= $title ?>">
			<span class="label label-info"><?= $infoCount ?></span>
		</a>
	<?php endif; ?>
</div>
