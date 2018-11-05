/**
 * The v-loading directive is used with <button> tag to show spinner
 * while the component is busy loading. The parent component
 * must have a "loading" boolean data property.
 */
module.exports = {
    /**
     * Component is ready.
     */
    bind (el, binding, vnode) {
        var element = el;

        vnode.context.$watch(binding.expression || 'saving', (val) => {
            if (val) {
                element.setAttribute('disabled', true);
                return;
            }

            element.removeAttribute('disabled');
        });
    }
};
