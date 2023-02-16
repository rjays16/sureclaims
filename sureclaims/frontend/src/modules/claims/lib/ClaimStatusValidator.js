export default class ClaimStatusValidator {
  static NOTFOUND = 'CLAIM SERIES NOT FOUND';

  /**
   * Valid as long as it is not empty or not equal to NOTFOUND
   */
  static isValid(status) {
    if (!status) {
      return false;
    }
    return !ClaimStatusValidator.isClaimNotFound(status);
  }

  static isClaimNotFound(status) {
    return status === ClaimStatusValidator.NOTFOUND;
  }
}
