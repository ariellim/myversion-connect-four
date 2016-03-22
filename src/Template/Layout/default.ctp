<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $this->fetch('title') ?></title>

	<?= $this->Html->css('app.css') ?>
	<?= $this->Html->css('foundation-flex.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
  </head>
  <body>
	<div class="container">
		<div class="row">
			<div class="small-12 large-expand columns">
				<?= $this->Flash->render() ?>
				<?= $this->fetch('content') ?>
			</div>
		</div>
	</div>
	<?= $this->Html->script('vendor/jquery.min.js') ?>
	<?= $this->Html->script('vendor/what-input.min.js') ?>
	<?= $this->Html->script('vendor/jquery.cookie.js') ?>
	<?= $this->Html->script('foundation.js') ?>
	<?= $this->Html->script('connectfour.js') ?>
    <script>
      $(document).foundation();
    </script>

  </body>
</html>