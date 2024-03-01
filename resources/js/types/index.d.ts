export interface Timestamps {
  created_at: string;
  updated_at: string;
}

export interface User extends Timestamps {
  id: number;
  name: string;
  email: string;
  email_verified_at: string;
}

export interface Ludus extends Timestamps {
  id: number;
  owner: number;
  name: string;
}

export interface Gladiator extends Timestamps {
  id: number;
  name: string;
  strength: number;
  defense: number;
  accuracy: number;
  evasion: number;
  ludus: number | null;
}

export interface MarketGladiator extends Gladiator {
  price: number;
  purchased: boolean;
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: User;
  };
};
