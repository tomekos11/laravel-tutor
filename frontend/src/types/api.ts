/**
 * Standard API response shape (Laravel apiResponse helper).
 */
export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
}

/**
 * User as returned by API (GET /api/me, PATCH /api/me, POST /api/login, etc.).
 * Matches Laravel User model JSON serialization.
 */
export interface UserFromApi {
  id: number;
  username: string;
  email: string | null;
  phone: string | null;
  name: string | null;
  surname: string | null;
  birthday: string | null;
  image: string | null;
  created_at: string;
  updated_at: string;
}

/**
 * User shape expected by user-store.setUser().
 * Required fields (email, username) are normalized from API nulls to empty string.
 * birthday is normalized to YYYY-MM-DD (no time part).
 */
export interface UserStoreUser {
  id: number;
  email: string;
  username: string;
  phone: string | null;
  name: string | null;
  surname: string | null;
  birthday: string | null;
  image: string | null;
  created_at: string;
  updated_at: string;
}

/** Normalizes API date (e.g. 1990-01-03T00:00:00.000000Z) to YYYY-MM-DD. */
export function toBirthdayOnly(value: string | null | undefined): string | null {
  if (value == null || value === '') return null;
  const trimmed = value.trim();
  if (!trimmed) return null;
  if (/^\d{4}-\d{2}-\d{2}$/.test(trimmed)) return trimmed;
  const iso = trimmed.slice(0, 10);
  return /^\d{4}-\d{2}-\d{2}$/.test(iso) ? iso : null;
}

export function toUserStoreUser(user: UserFromApi): UserStoreUser {
  return {
    id: user.id,
    email: user.email ?? '',
    username: user.username ?? '',
    phone: user.phone ?? null,
    name: user.name ?? null,
    surname: user.surname ?? null,
    birthday: toBirthdayOnly(user.birthday),
    image: user.image ?? null,
    created_at: user.created_at ?? '',
    updated_at: user.updated_at ?? '',
  };
}
