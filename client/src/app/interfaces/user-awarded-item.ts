export interface UserAwardedItem {
  id?: number;
  name?: string;
  bid?: number;
  closeDateTime?: string;
  winningBid?: {
    id?: number;
    bid?: number;
    isAutoBid?: boolean;
    dateTime?: string;
  };
}
