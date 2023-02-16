const menu = [
  /*
  {
    label: 'Members',
    type: 'header',
  },
  {
    label: 'New Record',
    link: {
      name: 'members-create',
    },
    icon: 'la la-user-plus',
  },
  */
  {
    label: 'Members/Patients',
    link: {
      name: 'members-list',
    },
    icon: ['far', 'users'],
    order: 1000,
  },
];

export default {
  '#eligibility': menu,
};
