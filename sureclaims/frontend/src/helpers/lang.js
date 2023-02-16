// -------------------------------------------------------------------------------------------
// Helper functions for natural language
// -------------------------------------------------------------------------------------------

/**
 * Converts a number to its ordinal equivalent
 *
 * Credits: https://ecommerce.shopify.com/c/ecommerce-design/t/ordinal-number-in-javascript-1st-2nd-3rd-4th-29259
 *
 * @param {Number} n
 * @returns {String}
 */
export const ordinalize = (n) => {
  const s = ['th', 'st', 'nd', 'rd'];
  const v = n % 100;
  return n + (s[(v - 20) % 10] || s[v] || s[0]);
};

export default {
  ordinalize,
};
