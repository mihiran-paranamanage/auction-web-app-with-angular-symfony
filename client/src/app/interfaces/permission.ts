export interface Permission {
  dataGroup: {
    canRead: boolean,
    canCreate: boolean,
    canUpdate: boolean,
    canDelete: boolean
  };
}
