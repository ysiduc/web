import { create } from 'zustand';
import { api, setCsrfToken } from '../api/client';
import type { User, ApiResponse } from '../types';

interface AuthState {
  user: User | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  error: string | null;
  darkMode: boolean;

  // Actions
  login: (username: string, password: string) => Promise<boolean>;
  logout: () => Promise<void>;
  checkAuth: () => Promise<void>;
  toggleDarkMode: () => void;
  setUser: (user: User | null) => void;
  clearError: () => void;
}

export const useAuthStore = create<AuthState>((set, get) => ({
  user: null,
  isAuthenticated: false,
  isLoading: true,
  error: null,
  darkMode: localStorage.getItem('pnmec_dark_mode') === 'true',

  login: async (username: string, password: string) => {
    set({ isLoading: true, error: null });
    try {
      const res = await api.post<ApiResponse<{ user: User; csrf_token?: string }>>('/auth/login.php', {
        username,
        password,
      });

      if (res.data.success && res.data.data.user) {
        if (res.data.data.csrf_token) {
          setCsrfToken(res.data.data.csrf_token);
        }
        set({
          user: res.data.data.user,
          isAuthenticated: true,
          isLoading: false,
          error: null,
        });
        return true;
      } else {
        set({
          error: res.data.message || 'Đăng nhập không thành công',
          isLoading: false,
        });
        return false;
      }
    } catch (err: any) {
      const msg = err.response?.data?.message || 'Lỗi kết nối máy chủ khi đăng nhập.';
      set({ error: msg, isLoading: false });
      return false;
    }
  },

  logout: async () => {
    try {
      await api.post('/auth/logout.php');
    } catch (e) {
      console.warn('Logout error', e);
    } finally {
      setCsrfToken(null);
      set({ user: null, isAuthenticated: false, error: null });
    }
  },

  checkAuth: async () => {
    set({ isLoading: true });
    try {
      const res = await api.get<ApiResponse<{ user: User; csrf_token?: string }>>('/auth/me.php');
      if (res.data.success && res.data.data.user) {
        if (res.data.data.csrf_token) {
          setCsrfToken(res.data.data.csrf_token);
        }
        set({
          user: res.data.data.user,
          isAuthenticated: true,
          isLoading: false,
        });
      } else {
        setCsrfToken(null);
        set({ user: null, isAuthenticated: false, isLoading: false });
      }
    } catch {
      setCsrfToken(null);
      set({ user: null, isAuthenticated: false, isLoading: false });
    }
  },

  toggleDarkMode: () => {
    const next = !get().darkMode;
    set({ darkMode: next });
    localStorage.setItem('pnmec_dark_mode', String(next));
    if (next) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  },

  setUser: (user) => set({ user }),
  clearError: () => set({ error: null }),
}));
