import {UserItem} from './user-item';
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
  items?: UserItem[];
  bids?: UserBid[];
  awardedItems?: UserAwardedItem[];
  accessToken?: string;
}
