import axios from 'axios';

// Detect API base URL dynamically based on current deployment environment
export const getApiBaseUrl = (): string => {
  if (import.meta.env.VITE_API_URL) {
    return import.meta.env.VITE_API_URL;
  }

  // In development mode (Vite dev server)
  if (import.meta.env.DEV) {
    return '/test/web_cty/api';
  }

  // In production: detect if running in /test/web_cty/ subpath or root
  const pathname = window.location.pathname;
  if (pathname.includes('/test/web_cty')) {
    return '/test/web_cty/api';
  }

  // If deployed in custom subfolder
  const segments = pathname.split('/').filter(Boolean);
  const adminIndex = segments.findIndex(s => s === 'admin-dashboard' || s === 'admin');
  if (adminIndex !== -1) {
    const rootPath = segments.slice(0, adminIndex).join('/');
    return rootPath ? `/${rootPath}/api` : '/api';
  }

  return '/api';
};

// Create Axios client
export const api = axios.create({
  baseURL: getApiBaseUrl(),
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
});

// Helper to resolve image URLs for public display
export const getImageUrl = (imagePath?: string | null): string => {
  if (!imagePath) return '';
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }

  const base = getApiBaseUrl().replace(/\/api\/?$/, '');

  // If path already starts with assets/
  if (imagePath.startsWith('assets/')) {
    return `${base}/${imagePath}`;
  }

  // If it's a default image or legacy image name
  if (imagePath.startsWith('default-') || imagePath.startsWith('service-') || imagePath.startsWith('home-') || imagePath.startsWith('logo')) {
    return `${base}/assets/images/${imagePath}`;
  }

  // Otherwise it's an uploaded image
  return `${base}/assets/uploads/${imagePath}`;
};
