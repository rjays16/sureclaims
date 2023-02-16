<?php

return [
    'attachSupportingDocuments' => \App\GraphQL\Mutation\AttachSupportingDocumentsMutation::class,
    'attachReturnDocuments' => \App\GraphQL\Mutation\AttachReturnDocumentsMutation::class,
    'checkEligibility' => \App\GraphQL\Mutation\CheckEligibilityMutation::class,
    'createClaim' => \App\GraphQL\Mutation\CreateClaimMutation::class,
    'createDoctor' => \App\GraphQL\Mutation\CreateDoctorMutation::class,
    'createPerson' => \App\GraphQL\Mutation\CreatePersonMutation::class,
    'createTransmittal' => \App\GraphQL\Mutation\CreateTransmittalMutation::class,
    'deleteClaim' => \App\GraphQL\Mutation\DeleteClaimMutation::class,
    'deleteDoctor' => \App\GraphQL\Mutation\DeleteDoctorMutation::class,
    'deletePerson' => \App\GraphQL\Mutation\DeletePersonMutation::class,
    'deleteSupportingDocument' => \App\GraphQL\Mutation\DeleteSupportingDocumentMutation::class,
    'deleteReturnDocument' => \App\GraphQL\Mutation\DeleteReturnDocumentMutation::class,
    'deleteTransmittal' => \App\GraphQL\Mutation\DeleteTransmittalMutation::class,
    'getDoctorPAN' => \App\GraphQL\Mutation\GetDoctorPANMutation::class,
    'refreshClaimStatus' => \App\GraphQL\Mutation\RefreshClaimStatusMutation::class,
    'toggleAutoTransmit' => \App\GraphQL\Mutation\ToggleAutoTransmitMutation::class,
    'transmit' => \App\GraphQL\Mutation\TransmitMutation::class,
    'updateClaim' => \App\GraphQL\Mutation\UpdateClaimMutation::class,
    'updateDoctor' => \App\GraphQL\Mutation\UpdateDoctorMutation::class,
    'updatePerson' => \App\GraphQL\Mutation\UpdatePersonMutation::class,
    'updateTransmittal' => \App\GraphQL\Mutation\UpdateTransmittalMutation::class,
    'uploadTransmittal' => \App\GraphQL\Mutation\UploadTransmittalMutation::class,
    'verifyPin' => \App\GraphQL\Mutation\VerifyPinMutation::class,
    'refile' => App\GraphQL\Mutation\RefileMutation::class,
];

