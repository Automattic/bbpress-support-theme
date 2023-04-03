const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

addFilter( 'blocks.registerBlockType', 'gutenberg-everywhere', function( settings, name ) {
	if ( name === 'core/paragraph' ) {
		return {
			...settings,
			attributes: {
				...settings.attributes,
				placeholder: {
					type: 'string',
					default: __( 'Start writing or type / to choose a block' ),
				},
			}
		};
	}

	return settings;
} );
