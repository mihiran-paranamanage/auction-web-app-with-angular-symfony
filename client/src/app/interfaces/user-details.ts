import {UserBid} from './user-bid';
import {UserAwardedItem} from './user-awarded-item';

export interface UserDetails {
  id?: number;
  userRoleId?: number;
  username?: string;
  password?: string;
  userRoleName?: string;
  email?: string;
  firstName?: string;
  lastName?: string;
  bids?: UserBid[];
  awardedItems?: UserAwardedItem[];
  accessToken?: string;
}
