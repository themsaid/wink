import Quill from 'quill';
let BlockEmbed = Quill.import('blots/block/embed');

class HTMLBlot extends BlockEmbed {
    static create(value) {
        let node = super.create();

        node.innerHTML = value.content;
        node.setAttribute('contenteditable', false);
        
        return node;
    }

    static value(node) {
        return {
            content: node.innerHTML
        };
    }
}

HTMLBlot.blotName = 'html';
HTMLBlot.tagName = 'div';
HTMLBlot.className = 'inline_html';

export default HTMLBlot;
