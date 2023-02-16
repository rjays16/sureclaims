import _isFunction from 'lodash/isFunction';
import _defaults from 'lodash/defaults';
import _kebabCase from 'lodash/kebabCase';

const normalizeKey = key => _kebabCase(key.replace(/Page$/, ''));

const defaultOptions = {
  moduleName: '',
  basePath: '',
};

export default {

  methods: {
    /**
     *
     * @param {Array} pages
     * @param {Object} options
     */
    pageRoutes(pages, options) {
      const routes = Object.keys(pages).map((pageKey) => {
        const page = pages[pageKey];
        const normalizedKey = normalizeKey(pageKey);
        const normalizedOptions = _defaults(options, defaultOptions);

        return {
          path: _isFunction(normalizedOptions.basePath) ?
            normalizedOptions.basePath(page, normalizedKey) :
            `${normalizedOptions.basePath}${normalizedKey}`,
          name: `${normalizedOptions.moduleName}-${normalizedKey}`,
          meta: {
            requiresAuth: true,
          },
        };
      });

      this.$router.addRoutes(routes);
    },
  },

};
