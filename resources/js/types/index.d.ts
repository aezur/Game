export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string;
}

export interface Ludus {
  id: number;
  owner: number;
  name: string;
}

export interface Gladiator {
  id: number;
  name: string;
  strength: number;
  defense: number;
  accuracy: number;
  evasion: number;
  ludus: number | null;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User;
  };
};
