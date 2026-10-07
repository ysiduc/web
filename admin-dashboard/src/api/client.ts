import axios from 'axios';

// Detect Application Base Path dynamically (e.g. '/test/web_cty' on local, '' on root domain, '/sub' on subfolder)
export const getAppBasePath = (): string => {
  if (import.meta.env.VITE_APP_BASE_URL !== undefined) {
    return import.meta.env.VITE_APP_BASE_URL.replace(/\/$/, '');
  }

  // In development mode (Vite dev server)
  if (import.meta.env.DEV) {
    return '/test/web_cty';
  }

  // In production: detect if running in subpath
  const pathname = window.location.pathname;
  if (pathname.includes('/test/web_cty')) {
    return '/test/web_cty';
  }

  const segments = pathname.split('/').filter(Boolean);
  const adminIndex = segments.findIndex(s => s === 'admin-dashboard' || s === 'admin');
  if (adminIndex !== -1) {
    const rootPath = segments.slice(0, adminIndex).join('/');
    return rootPath ? `/${rootPath}` : '';
  }

  return '';
};

// Detect API base URL dynamically based on current deployment environment
export const getApiBaseUrl = (): string => {
  if (import.meta.env.VITE_API_URL) {
    return import.meta.env.VITE_API_URL;
  }
  const base = getAppBasePath();
  return `${base}/api`;
};

// Helper to generate public frontend URLs (e.g. '/index.php', '/project-detail.php?id=1')
export const getPublicPageUrl = (path: string): string => {
  const base = getAppBasePath();
  const cleanPath = path.startsWith('/') ? path : `/${path}`;
  return `${base}${cleanPath}`;
};

// CSRF Token Management
let currentCsrfToken: string | null = null;
export const setCsrfToken = (token: string | null) => {
  currentCsrfToken = token;
};
export const getCsrfToken = (): string | null => currentCsrfToken;

// Create Axios client
export const api = axios.create({
  baseURL: getApiBaseUrl(),
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
});

// Attach CSRF token on mutating requests
api.interceptors.request.use((config) => {
  const method = (config.method || '').toLowerCase();
  if (currentCsrfToken && ['post', 'put', 'patch', 'delete'].includes(method)) {
    config.headers['X-CSRF-Token'] = currentCsrfToken;
  }
  return config;
});

// Helper to resolve image URLs for public display
export const getImageUrl = (imagePath?: string | null): string => {
  if (!imagePath) return '';
  const trimmed = imagePath.trim();
  if (
    !trimmed ||
    trimmed === 'default-service.jpg' ||
    trimmed === 'default-project.jpg' ||
    trimmed === 'default-news.jpg'
  ) {
    return '';
  }

  if (trimmed.startsWith('http://') || trimmed.startsWith('https://') || trimmed.startsWith('//')) {
    return trimmed;
  }

  const base = getAppBasePath();

  // If path already starts with /assets/
  if (trimmed.startsWith('/assets/')) {
    return `${base}${trimmed}`;
  }

  // If path already starts with assets/
  if (trimmed.startsWith('assets/')) {
    return `${base}/${trimmed}`;
  }

  // If it's a known static asset in assets/images/
  if (trimmed.startsWith('service-') || trimmed.startsWith('home-') || trimmed.startsWith('logo')) {
    return `${base}/assets/images/${trimmed}`;
  }

  // Otherwise it's an uploaded image (e.g. services/xxx.jpg, projects/xxx.jpg, news/xxx.jpg)
  const cleanPath = trimmed.replace(/^\/+/, '').replace(/^uploads\//, '');
  return `${base}/assets/uploads/${cleanPath}`;
};
