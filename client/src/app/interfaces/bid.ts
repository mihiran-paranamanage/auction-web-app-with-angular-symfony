export interface Bid {
  id?: number;
  userId?: number;
  username?: string;
  itemId?: number;
  itemName?: string;
  bid?: number;
  isAutoBid?: boolean;
  dateTime?: string;
  accessToken?: string;
}
