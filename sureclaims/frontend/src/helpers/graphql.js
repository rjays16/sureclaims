// -------------------------------------------------------------------------------------------
// Helper functions for GraphQL
// -------------------------------------------------------------------------------------------

/**
 * Returns a user-friendly error message based on the error
 * object thrown by GraphQL operations
 *
 * @param {Error} error
 *
 * @return {String}
 */
export const formatError = error =>
  error.message.replace(/^GraphQL error:\s+/, '');

export default {
  formatError,
};
