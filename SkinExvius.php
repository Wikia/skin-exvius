<?php
/**
 * Exvius skin
 *
 * @file
 * @ingroup Skins
 */

class SkinExvius extends SkinTemplate {
	public $skinname = 'exvius';
	public $stylename = 'exvius';
	public $template = 'ExviusTemplate';

	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		// add viewport and scripts
		// $out->addMeta('viewport', 'width=device-width, user-scalable=yes, initial-scale=1.0');
		$out->addModules(['skins.exvius.scripts']);
	}

	/**
	 * @param $out OutputPage object
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		// add google font and styles
		$out->addStyle('https://fonts.googleapis.com/css?family=Lato:400,700');
		$out->addStyle('https://fonts.googleapis.com/css?family=Merriweather:400,700');
		$styles = ['skins.exvius.styles'];
		Hooks::run( 'SkinVectorStyleModules', [ $this, &$styles ] );
		$out->addModuleStyles($styles);
	}
}
