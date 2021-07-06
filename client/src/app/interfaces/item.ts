export interface Item {
  id?: number;
  name?: string;
  description?: string;
  price?: number;
  bid?: number;
  closeDateTime?: string;
  isAutoBidEnabled?: boolean;
  isClosed?: boolean;
  isAwardNotified?: boolean;
  awardedUserId?: number | null;
  awardedUsername?: string | null;
  awardedUserRoleId?: number | null;
  awardedUserRoleName?: string | null;
  awardedUserEmail?: string | null;
  awardedUserFirstName?: string | null;
  awardedUserLastName?: string | null;
  accessToken?: string;
}
