/**
 * WordPress dependencies
 */
import { isUnmodifiedDefaultBlock } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';

export const __experimentalUsePageTemplatePickerAvailable = () => {
	return useSelect( ( select ) => {
		const { getCurrentPostType } = select( 'core/editor' );
		return getCurrentPostType() === 'page';
	}, [] );
};

export const __experimentalUsePageTemplatePickerVisible = () => {
	const { blocks, firstBlock, isEmptyBlockList } = useSelect( ( select ) => {
		const { getBlockOrder, getBlock } = select( 'core/block-editor' );

		const _blocks = getBlockOrder();
		const _isEmptyBlockList = _blocks.length === 0;
		const _firstBlock = ! _isEmptyBlockList && getBlock( _blocks[ 0 ] );

		return {
			blocks: _blocks,
			firstBlock: _firstBlock,
			isEmptyBlockList: _isEmptyBlockList,
		};
	}, [] );

	const isOnlyUnmodifiedDefault =
		blocks.length === 1 && isUnmodifiedDefaultBlock( firstBlock );
	const isEmptyContent = isEmptyBlockList || isOnlyUnmodifiedDefault;
	const isTemplatePickerAvailable = __experimentalUsePageTemplatePickerAvailable();

	return isEmptyContent && isTemplatePickerAvailable;
};
