export interface UserAwardedItem {
  id?: number;
  name?: string;
  description?: string;
  price?: number;
  bid?: number;
  closeDateTime?: string;
  isAutoBidEnabled?: boolean;
  isClosed?: boolean;
  isAwardNotified?: boolean;
  winningBidId?: number;
  winningBid?: number;
  winningBidIsAutoBid?: boolean;
  winningBidDateTime?: string;
}
