export interface Game {
  id: string;
  title: string;
  description: string;
  type: string;
  genre: string;
  systemRequirements: {
    os: string;
    processor: string;
    memory: string;
    graphics: string;
    storage: string;
  };
  coverImage: string;
  isFeatured?: boolean;
}

export interface User {
  id: string;
  username: string;
  avatar: string;
  level: number;
  status: 'online' | 'offline' | 'away';
  friends: number;
  gamesOwned: number;
}