const top = [
  {
    id: '#eligibility',
    label: 'Eligibility',
    icon: null,
    order: 50,
    items: [
      {
        label: 'PIN Verification',
        link: {
          name: 'eligibility-verify-pin',
        },
        icon: ['far', 'hashtag'],
        active: false,
        order: 2000,
      },
      {
        label: 'Check Eligibility',
        link: {
          name: 'eligibility-check',
        },
        icon: ['far', 'check-circle'],
        active: false,
        order: 3000,
      },
      /*
      {
        label: 'Check SPC',
        link: {
          name: 'eligibility-check-spc',
        },
        icon: ['far', 'calendar-check'],
        active: false,
        order: 3000,
      },
      */
    ],
  },
];

export default {
  top,
};
