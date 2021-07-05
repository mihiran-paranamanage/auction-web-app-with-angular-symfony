export interface UserBid {
  id?: number;
  bid?: number;
  isAutoBid?: boolean;
  dateTime?: string;
  item?: {
    id?: number;
    name?: string;
    status?: string;
    closeDateTime?: string;
  };
}
