import Quill from 'quill'
import DOMPurify from 'dompurify';

const Link = Quill.import('formats/link');

// Inspired by https://codepen.io/anon/pen/GNMXZa?__cf_chl_jschl_tk__=42b291790171908cf2f128f6aff4d78e6a2723fb-1589576797-0-AbEVaGl8yJDXzW3aSFJ8WD9id6PakeZ2fSXBBOoAaJ_mTZUsuAVzCZdUlkEYJ-Q5evt2n6zNpiQlz7VvmILh6-cclhloFqawsRPA2H4F1aqT3G2fQ9GOvPdI8Q1ShHkP8-pKz8F5tPUsQy0sd4Y4fgSDS_OkrsGALxMQKF3Tum378uOf9sE3fkxJvy9QvgSx2i1zs9ZDNTIXhtvaur0R6-e44ZOF0NtCVJfrHgNjqUZZpbdzXN7H6O9HhCoJj-sn9YL7cPicUb2qAoDxsqMGF8NqlYsQtBKNj374tv7SM9cmVZCyfun1n1SoxGBF6-0LNRTSKQCX4F-m72WUGvQ920lxQqZZz29OtHwnD_ys4f6Z
class WinkLink extends Link {
    static create(value) {
        let node = super.create(value);

        value = this.sanitize(value);

        node.setAttribute('href', value);

        node.removeAttribute('target');

        return node;
    }

    format(name, value) {
        super.format(name, value);
        this["domNode"].removeAttribute("target");
    }
}

export default WinkLink
