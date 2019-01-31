import Quill from 'quill';

const BlockEmbed = Quill.import('blots/block/embed');

class GistBlot extends BlockEmbed {
    static create(value) {
        const node = super.create();
        node.innerHTML = this.createScript(value);
        node.setAttribute('data-gist-url', this.getGistUrl(value));
        node.setAttribute('contenteditable', false);
        return node;
    }

    static createScript(code) {
        const url = this.getGistUrl(code);
        return '<script src="' + url + '"><\/script>';
    }

    static getGistUrl(code) {
        return 'https://gist.github.com/' + code + '.js';
    }

    static value(node) {
        return node.innerHTML;
    }
}

GistBlot.blotName = 'gist';
GistBlot.tagName = 'div';
GistBlot.className = 'gist';

export default GistBlot;
