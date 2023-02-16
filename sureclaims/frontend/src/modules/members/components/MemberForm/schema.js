export const model = {
  pPIN: '',
  pMemberLastName: '',
  pMemberFirstName: '',
  pMemberMiddleName: '',
  pMemberSuffix: '',
  pMemberBirthDate: '',
  pMailingAddress: '',
  pZipCode: '',
  pPatientIs: '',
  pAdmissionDate: '',
  pDischargeDate: '',
  pPatientLastName: '',
  pPatientFirstName: '',
  pPatientMiddleName: '',
  pPatientSuffix: '',
  pPatientBirthDate: '',
  pPatientGender: '',
  pMemberShipType: '',
  pPEN: '',
  pEmployerName: '',
  pRVS: '',
  pTotalAmountActual: '',
  pTotalAmountClaimed: '',
  pIsFinal: false,
};

export const schema = {
  pPIN: {
    required: true,
    validate: {
      as: 'PHIC No',
      rules: {
        required: true,
      },
    },
  },

  pMemberLastName: {
    required: true,
    validate: {
      rules: {
        required: true,
      },
      as: 'Member Last Name',
    },
  },

  pMemberFirstName: {
    label: 'First Name',
    required: true,
    validate: {
      rules: {
        required: true,
      },
      as: 'Member First Name',
    },
  },

  pMemberMiddleName: {
    label: 'Middle Name',
    validate: {
      as: 'Member Middle Name',
    },
  },

  pMemberSuffix: {
    label: 'Suffix',
    validate: {
      as: 'Member Suffix',
    },
  },

  pMemberBirthDate: {
    label: 'Birth Date',
    validate: {
      rules: {
        required: true,
      },
      as: 'Member Birth Date',
    },
    hint: 'Format: MM/DD/YYYY',
  },

  pMailingAddress: {
    label: 'Mailing Address',
    validate: {
      rules: {},
      as: 'Member Address',
    },
  },

  pZipCode: {
    label: 'Zip Code',
    validate: {
      as: 'Zip Code',
      rules: {
        numeric: true,
      },
    },
  },

  pAdmissionDate: {
    label: 'Admission Date',
    required: true,
    validate: {
      rules: {
        required: true,
      },
      as: 'Admission Date',
    },
    hint: 'Format: MM/DD/YYYY',
  },


  pDischargeDate: {
    label: 'Discharge Date',
    validate: {
      rules: {
        required: false,
      },
      as: 'Discharge Date',
    },
    hint: 'Format: MM/DD/YYYY',
  },


  pPatientIs: {
    label: 'Patient Is',
    required: true,
    validate: {
      as: 'Patient Type',
      rules: 'required',
    },
    items: {
      M: 'Member (Self)',
      S: 'Spouse',
      C: 'Child',
      P: 'Parent',
    },
  },

  pPatientLastName: {
    label: 'Last Name',
    required: true,
    validate: {
      rules: {
        required: true,
      },
      as: 'Patient Last Name',
    },
  },

  pPatientFirstName: {
    label: 'First Name',
    required: true,
    validate: {
      rules: {
        required: true,
      },
      as: 'Patient First Name',
    },
  },

  pPatientMiddleName: {
    label: 'Middle Name',
    validate: {
      as: 'Patient Middle Name',
    },
  },

  pPatientSuffix: {
    label: 'Suffix',
    validate: {
      as: 'Patient Suffix',
    },
  },

  pPatientBirthDate: {
    label: 'Birth Date',
    validate: {
      rules: {
        required: true,
      },
      as: 'Patient Birth Date',
    },
    hint: 'Format: MM/DD/YYYY',
  },

  pPatientGender: {
    label: 'Gender',
    required: true,
    validate: {
      as: 'Patient Gender',
      rules: 'required',
    },
    items: {
      M: {
        label: 'Male',
      },
      F: {
        label: 'Female',
      },
    },
  },

  pMemberShipType: {
    label: 'Membership Type',
    required: true,
    validate: {
      as: 'Membership Type',
      rules: {
        required: true,
        in: ['S', 'G'],
      },
    },
    items: {
      S: { label: 'Employed Private' },
      G: { label: 'Employer Government' },
      I: { label: 'Indigent' },
      NS: { label: 'Individually Paying' },
      NO: { label: 'OFW' },
      PS: { label: 'Non Paying Private' },
      PG: { label: 'Non Paying Government' },
      P: { label: 'Lifetime member' },
    },
  },

  pPEN: {
    label: 'PEN',
    hint: 'PhilHealth Employer Number',
    validate: {
      as: 'PHIC Employer No',
    },
  },

  pEmployerName: {
    label: 'Employer Name',
    validate: {
      as: 'Employer Name',
    },
  },

  pRVS: {
    label: 'RVS Code',
    hint: 'Leave blank if no surgery is to be done',
    validate: {
      as: 'RVS Code',
    },
  },

  pTotalAmountActual: {
    label: 'Total Actual Amount',
    validate: {
      as: 'Total Actual Amount',
    },
  },

  pTotalAmountClaimed: {
    label: 'Total Claim Amount',
    hint: 'Hospital charges only',
    validate: {
      as: 'Total Claim Amount',
    },
  },

  pIsFinal: {
    validate: {
      as: 'Is Final',
    },
  },
};
