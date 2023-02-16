import BaseComponent from '@/modules/core/mixins/BaseComponent';

export default {
  mixins: [BaseComponent],
  methods: {
    /**
     * Removes unnecesarry propertiesin in claim
     */
    cleanClaim() {
      // CONSUMPTION
      // const pEnoughBenefits = this.field('claim.CF2.CONSUMPTION.pEnoughBenefits', 'N');
      // if (pEnoughBenefits === 'N') {
      //   this.removeField('claim.CF2.CONSUMPTION.BENEFITS');
      // } else {
      //   this.removeField('claim.CF2.CONSUMPTION.HCIFEES');
      //   this.removeField('claim.CF2.CONSUMPTION.PROFFEES');
      //   this.removeField('claim.CF2.CONSUMPTION.PURCHASES');
      // }
      // END CONSUMPTION

    }
  }
};
