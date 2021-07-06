export interface UserBid {
  id?: number;
  userId?: number;
  username?: string;
  itemId?: number;
  itemName?: string;
  itemStatus?: string;
  itemCloseDateTime?: string;
  bid?: number;
  isAutoBid?: boolean;
  dateTime?: string;
}
