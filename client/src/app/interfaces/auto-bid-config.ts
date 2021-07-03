export interface AutoBidConfig {
  id?: number;
  maxBidAmount?: number;
  currentBidAmount?: number;
  notifyPercentage?: number;
  isAutoBidEnabled?: boolean;
  accessToken?: string;
}
