import Quill from 'quill';

let BlockEmbed = Quill.import('blots/block/embed');

class VideoBlot extends BlockEmbed {
    static create(value) {
        let node = super.create();

        node.setAttribute('contenteditable', false);

        let video = document.createElement('video');
        let source = document.createElement('source');
        video.setAttribute('controls', 'controls');
        source.setAttribute('src', value.url);
        video.appendChild(source);
        node.appendChild(video);

        return node;
    }

    static value(node) {
        console.log(node);
        let video = node.querySelector('source');

        return {
            url: video.getAttribute('src'),
        };
    }
}

VideoBlot.tagName = 'div';
VideoBlot.blotName = 'video';
VideoBlot.className = 'embedded_video';

export default VideoBlot;
