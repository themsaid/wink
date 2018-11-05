/**
 * src: https://jsfiddle.net/Linusborg/Lx49LaL8/
 */
module.exports = {
    bind (el, binding, vnode) {
        if (typeof binding.value !== 'function') {
            const compName = vnode.context.name

            let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`

            if (compName) {
                warn += `Found in component '${compName}'`
            }
        }

        const bubble = binding.modifiers.bubble

        const handler = (e) => {
            if (bubble || (!el.contains(e.target) && el !== e.target)) {
                binding.value(e)
            }
        };

        el.__vueClickOutside__ = handler;

        document.addEventListener('click', handler)
    },

    unbind: function (el, binding) {
        document.removeEventListener('click', el.__vueClickOutside__)

        el.__vueClickOutside__ = null
    }
};
