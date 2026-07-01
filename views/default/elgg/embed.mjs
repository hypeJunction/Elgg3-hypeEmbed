/**
 * Elgg 7 removed the core `elgg/embed` AMD module that held the shared state of
 * which textarea the embed modal is currently targeting. The hypeembed tab
 * modules (tab/code, tab/player, lists/item, file_upload/content, tab/buttons)
 * still read `embed.textAreaId` to know where to insert the chosen content.
 *
 * Recreate that minimal shared singleton here. `embed/toolbar.mjs` sets
 * `embed.textAreaId` from the clicked `.embed-toolbar[data-textarea-id]` before
 * the tab content (which reads it) is loaded.
 */
const embed = {
	textAreaId: null,
};

export default embed;
