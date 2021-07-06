export interface AutoBidConfig {
  id?: number;
  userId?: number;
  username?: string;
  maxBidAmount?: number;
  currentBidAmount?: number;
  notifyPercentage?: number;
  isAutoBidEnabled?: boolean;
  isMaxBidExceedNotified?: boolean;
  accessToken?: string;
}
