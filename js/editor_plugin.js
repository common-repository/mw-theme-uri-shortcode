jQuery( function() {
	if ( typeof template_directory !== 'undefined' && typeof stylesheet_directory !== 'undefined' && template_directory != '' && stylesheet_directory != '' ) {
		var methods = {
			getInfo: function() {
				return {
					longname : 'MW Theme URI Shortcode',
					author   : 'Takashi Kitajima',
					authorurl: 'http://2inc.org',
					infourl  : 'http://2inc.org',
					version  : '1.0.0'
				};
			},
			init: function( ed, url ) {
				ed.onBeforeSetContent.add( function( ed, o ) {
					o.content = methods.onBeforeSetContent( o.content );
				} );

				ed.onPostProcess.add( function( ed, o ) {
					if ( o.get )
						o.content = methods.onPostProcess( o.content );
				} );
			},
			onBeforeSetContent: function( content ) {
				content = methods._onBeforeSetContent(
					content, 'template_directory_uri', template_directory
				);
				content = methods._onBeforeSetContent(
					content, 'stylesheet_directory_uri', stylesheet_directory
				);
				content = methods._onBeforeSetContent(
					content, 'theme_directory', stylesheet_directory
				);
				return content;
			},
			_onBeforeSetContent: function( content, shortcode, dirname ) {
				var reg = new RegExp( '\\[' + shortcode + '\\]', 'g' );
				return content.replace( reg, function( a, b ) {
					return tinymce.baseURL.replace(
						/(.+?)wp-includes\/js\/tinymce/i,
						'$1wp-content/themes/' + dirname
					);
				} );
			},
			onPostProcess: function( content ) {
				content = methods._onPostProcess(
					content, 'template_directory_uri', template_directory
				);
				content = methods._onPostProcess(
					content, 'stylesheet_directory_uri', stylesheet_directory
				);
				content = methods._onPostProcess(
					content, 'theme_directory', stylesheet_directory
				);
				return content;
			},
			_onPostProcess: function( content, shortcode, dirname ) {
				var str = tinymce.baseURL.replace(
					/(.+?)wp-includes\/js\/tinymce/i,
					'$1wp-content/themes/' + dirname
				);
				var reg = new RegExp( str, 'g' );
				return content.replace( reg, function( a ) {
					return '[' + shortcode + ']';
				} );
			}
		}
		tinymce.create( 'tinymce.plugins.mwThemeUriShortcode', methods );
		tinymce.PluginManager.add( 'mwThemeUriShortcode', tinymce.plugins.mwThemeUriShortcode );
	}
} );
