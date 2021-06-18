export interface ItemBid {
  id?: number;
  itemId?: number;
  userId?: number;
  bid?: number;
  isAutoBid?: boolean;
  dateTime?: string;
  user?: {userId?: number, username?: string};
  accessToken?: string;
}
