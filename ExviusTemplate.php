<?php
/**
 * Exvius skin
 *
 * @file
 * @ingroup Skins
 */

class ExviusTemplate extends BaseTemplate {
	/**
	 * Template filter callback for this skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 */
	public function execute() {
		$this->html( 'headelement' );
		?>

		<header>
			<div class="header-top">
				<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" title="<?php $this->text('sitename'); ?>" rel="home">
					<img src="<?php echo $this->data['stylepath'] . "/Exvius/resources/images/site-logo.jpg"; ?>" alt="<?php $this->text('sitename'); ?>" width="1020" height="184">
				</a>
			</div>
			<div class="header-bottom">
				<nav>
					<div class="row">
						<div class="column small-8">
							<ul id="menu">
								<li>
									<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" title="<?php $this->text('sitename'); ?>" rel="home">Home</a>
								</li>
								<?php
								foreach ( $this->getSidebar() as $boxName => $box ) { ?>
									<?php if ($boxName != 'TOOLBOX') { ?>
										<li>
											<a style="cursor:default;"><?php echo htmlspecialchars( $box['header'] ); ?></a>

											<?php
												if ( is_array( $box['content'] ) ) { ?>
												<ul class="sub-menu">
													<?php
														foreach ( $box['content'] as $key => $item ) {
															echo $this->makeListItem( $key, $item );
														}
													?>
												</ul>
											<?php
												} else {
													echo $box['content'];
												}
											?>
										<li>
									<?php } ?>
								<?php } ?>
							</ul>
						</div>
						<div class="column small-4">
							<form action="<?php $this->text( 'wgScript' ); ?>" id="searchform">
								<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
								<?php echo $this->makeSearchButton( 'go', array(
									'value' => "Submit",
									'class' => "searchButton",
									'id'	=> "searchsubmit",
								) ); ?>
								<?php echo $this->makeSearchInput( array( 'type' => 'text', 'id' => 's', 'placeholder' => 'Search') ); ?>
							</form>
						</div>
					</div>
				</nav>
			</div>
		</header>
	<div id="global-wrapper"<?php echo ($this->data['showads'] && HydraHooks::showSideRailAPUs($this->getSkin()) && $this->config->get('HydraSkinShowSideRail') ? ' class="with-siderail"' : '') ?>>
		<div id="content" class="mw-body" role="main">
			<div class="row">
				<div class="column">
					<ul id="header-tools">
						<li>
							<a>Wiki</a>
							<ul class="sub-menu">
								<li class="label">Action</li>
								<?php
									foreach ( $this->data['content_actions'] as $key => $tab ) {
										echo $this->makeListItem( $key, $tab, array( 'tag' => 'li' ) );
									}
								?>
								<li class="label">Tools</li>
								<?php
									foreach ( $this->getToolbox() as $key => $tbitem ) {
										if ($key != 'print' && $key != 'permalink') {
											echo $this->makeListItem( $key, $tbitem );
										}
									}
								?>
								<li><?php echo Linker::specialLink('RecentChanges'); ?></li>
								<li class="label">Account</li>
								<?php
									foreach ( $this->getPersonalTools() as $key => $item ) {
										echo $this->makeListItem( $key, $item );
									}
								?>
							</ul>
						</li>
						<?php
							foreach ( $this->data['content_actions'] as $key => $tab ) {
								if ($key == 'talk') {
									echo $this->makeListItem( $key, $tab, array( 'tag' => 'li' ) );
								}
							}
						?>
					</ul>
					<div class="clearfix"></div>
					<div class="main-content">
						<div id="crusades-1">
							<!-- ATF Leaderboard -->
							<?php if ($this->data['showads'] && HydraHooks::getAdBySlot('atflb')) { ?>
							<div id="atflb">
								<?php echo HydraHooks::getAdBySlot('atflb'); ?>
							</div>
							<?php } ?>
							<!-- /ATF Leaderboard -->
						</div>

						<?php $this->html( 'sitenotice' ); ?>
						<h1 id="firstHeading" class="firstHeading"><?php $this->html( 'title' ); ?></h1>
						<?php $this->html( 'prebodyhtml' ) ?>
						<div id="bodyContent" class="mw-body-content">
							<?php if ( $this->data['subtitle'] ) { ?>
								<div class="subtitle"><?php $this->html( 'subtitle' ) ?></div>
							<?php } ?>
							<?php
							$this->html( 'bodycontent' );

							if ( $this->data['dataAfterContent'] ) {
								$this->html( 'dataAfterContent' );
							}

							if ( $this->data['catlinks'] ) {
								$this->html( 'catlinks' );
							}
							?>
							<div class="visualClear"></div>
							<?php $this->html( 'debughtml' ); ?>
						</div>
						<?php
						if ($this->data['showads'] && HydraHooks::showSideRailAPUs($this->getSkin())) {
						?>
						<div id="siderail">
							<?php
							/* $$placements['new-item'] = $rawHtml;
							 * Item key should be suitable as an element ID.
							 * NOTE: Do not sort the placements array!  Some extensions will insert their content in a specific order.
							 *
							 * Example:
							 * <div id="new-item">
							 *		<img src='htmlexample.png'/>
							 * </div>
							*/
							$placements = [];
							Hooks::run('SideRailPlacements', [&$placements]);

							//Give extensions a chance to sort the placements correctly.
							Hooks::run('SideRailPlacementsBeforeOutput', [&$placements]);

							if (is_array($placements) && count($placements)) {
								foreach ($placements as $id => $placement) {
									echo "<div id=".htmlentities($id).">".$placement."</div>";
								}
							}
							?>
						</div>
						<div class="visualClear"></div>
						<?php
						}

						$placements = [];
						Hooks::run('BottomPlacements', [&$placements, &$this]);

						//Give extensions a chance to sort the placements correctly.
						Hooks::run('BottomPlacementsBeforeOutput', [&$placements, &$this]);

						if (is_array($placements) && count($placements)) {
							foreach ($placements as $id => $placement) {
								echo "<div id=".htmlentities($id).">".$placement."</div>";
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<div id="exvius_footer">
			<div class="row">
				<div class="column small-3">
					<h3>Action</h3>
					<ul>
					<?php
						foreach ( $this->data['content_actions'] as $key => $tab ) {
							echo $this->makeListItem( $key, $tab, array( 'tag' => 'li' ) );
						}
					?>
					</ul>
				</div>
				<div class="column small-3">
					<h3>Tools</h3>
					<ul>
						<?php
							foreach ( $this->getToolbox() as $key => $tbitem ) {
								if ($key != 'print' && $key != 'permalink') {
									echo $this->makeListItem( $key, $tbitem );
								}
							}
						?>
						<li><?php echo Linker::specialLink('RecentChanges'); ?></li>
						<li><?php $this->html( 'privacy' ) ?></li>
						<li><?php $this->html( 'about' ) ?></li>
					</ul>
				</div>
				<div id="crusades-3">
					<?php if ($showAds) { ?>
						<div class="ad-placement ad-main-med-rect-footer">
							<?= HydraHooks::getAdBySlot('footermrec') ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
		<?php $this->printTrail(); ?>

		<?php
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
	}
}
