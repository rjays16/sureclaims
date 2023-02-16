// -------------------------------------------------------------------------------------------
// Helper functions for number and currency processing
// -------------------------------------------------------------------------------------------

import numeral from 'numeral';

numeral.register('locale', 'en-ph', {
  delimiters: {
    thousands: ',',
    decimal: '.'
  },
  abbreviations: {
    thousand: 'k',
    million: 'm',
    billion: 'b',
    trillion: 't'
  },
  currency: {
    symbol: '₱'
  },
});

numeral.locale('en-ph');

/**
 * Formats a number to user-friendly format
 *
 * @param {Number} number
 * @returns {String}
 */
export const format = number => numeral(number).format('0,0');

/**
 * Formats a number to the default currency format
 *
 * @param {Number} number
 * @returns {String}
 */
export const formatCurrency = number => numeral(number).format('($0,0.00)');

/**
 * Formats a number to byte format
 *
 * @param {Number} number
 * @returns {String}
 */
export const formatBytes = number => numeral(number).format('0b');

export default {
  format,
  formatBytes,
  formatCurrency,
};
