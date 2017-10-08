<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="admin-content">
	<div class="admin-content-body">
		<div class="am-cf am-padding am-padding-bottom-0">
			<div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">系统出错了</strong> / <small><?php echo $severity, "\n"; ?></small></div>
		</div>

		<hr>

		<div class="am-g">
			<div class="am-u-sm-12">
				<h2 class="am-text-center am-text-xxxl am-margin-top-lg">404. Not Found</h2>
				<p class="am-text-center"><?php echo $message, "\n"; ?></p>

				Severity:    <?php echo $severity, "\n"; ?>
				Filename:    <?php echo $filepath, "\n"; ?>
				Line Number: <?php echo $line; ?>
			</div>
		</div>
	</div>

	<footer class="admin-content-footer">
		<hr>
		<p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
	</footer>
</div>

<!--A PHP Error was encountered-->
<!---->
<!--Severity:    --><?php //echo $severity, "\n"; ?>
<!--Message:     --><?php //echo $message, "\n"; ?>
<!--Filename:    --><?php //echo $filepath, "\n"; ?>
<!--Line Number: --><?php //echo $line; ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

Backtrace:
<?php	foreach (debug_backtrace() as $error): ?>
<?php		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
	File: <?php echo $error['file'], "\n"; ?>
	Line: <?php echo $error['line'], "\n"; ?>
	Function: <?php echo $error['function'], "\n\n"; ?>
<?php		endif ?>
<?php	endforeach ?>

<?php endif ?>
