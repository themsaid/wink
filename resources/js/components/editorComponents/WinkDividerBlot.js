import Quill from 'quill';

let BlockEmbed = Quill.import('blots/block/embed');

class WinkDividerBlot extends BlockEmbed {
}

WinkDividerBlot.blotName = 'divider';
WinkDividerBlot.tagName = 'hr';

export default WinkDividerBlot;
