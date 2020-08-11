import Quill from 'quill'
import DOMPurify from 'dompurify';

const Clipboard = Quill.import('modules/clipboard')
const Delta = Quill.import('delta')

// Inspired by https://github.com/Artem-Schander/quill-paste-smart/blob/master/src/quill-paste-smart.js
class WinkClipboard extends Clipboard {
    onPaste(e) {
        let delta;
        let text;
        let html;
        let range;

        e.preventDefault()

        range = this.quill.getSelection()
        text = e.clipboardData.getData('text/plain')
        html = e.clipboardData.getData('text/html')

        delta = new Delta().retain(range.index).delete(range.length);

        if (html) {
            delta = delta.concat(this.convert(
                DOMPurify.sanitize(html, this.getAllowed())
            ));
        } else {
            delta = delta.insert(text);
        }

        this.quill.updateContents(delta)
        this.quill.setSelection(range.index + text.length, 0)
        this.quill.scrollIntoView()
    }

    getAllowed() {
        let tidy = {};

        tidy.ALLOWED_TAGS = [
            'p',
            'br',
            'span',
            'b', 'strong',
            'i', 'u', 's',
            'h2', 'h3',
            'pre',
            'ol', 'ul', 'li',
            'a',
            'img',
            'blockquote'

        ];

        tidy.ALLOWED_ATTR = [
            'class', 'spellcheck', 'href', 'rel',
            'src', 'title', 'alt'
        ];

        return tidy;
    }
}

export default WinkClipboard
