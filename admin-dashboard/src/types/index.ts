export interface User {
  id: number;
  username: string;
  fullname: string;
  email: string;
  phone?: string | null;
  role: 'admin' | 'staff';
  status: 'active' | 'inactive';
  created_at?: string;
}

export interface Project {
  id: number;
  title: string;
  slug: string;
  category: string;
  client?: string | null;
  location?: string | null;
  start_date?: string | null;
  completion_date?: string | null;
  description: string;
  content?: string | null;
  image: string;
  gallery?: string | null;
  status: 'published' | 'draft';
  views: number;
  detail_mode?: 'basic' | 'custom';
  detail_blocks?: string | DetailBlock[] | null;
  created_by?: number | null;
  author_name?: string | null;
  created_at: string;
}

export type BlockType = 'heading' | 'paragraph' | 'image' | 'gallery' | 'callout' | 'divider' | 'html';

export interface GalleryItem {
  src: string;
  alt?: string;
  caption?: string;
}

export interface DetailBlock {
  id: string;
  type: BlockType;
  level?: 2 | 3 | 4;
  text?: string;
  src?: string;
  alt?: string;
  caption?: string;
  images?: GalleryItem[];
  title?: string;
  variant?: 'info' | 'warning' | 'success' | 'gold';
  content?: string;
}

export interface Service {
  id: number;
  title: string;
  slug: string;
  code?: string | null;
  summary?: string | null;
  content?: string | null;
  image: string;
  featured: number | boolean;
  views: number;
  status?: 'active' | 'inactive';
  created_at: string;
}

export interface NewsItem {
  id: number;
  title: string;
  slug: string;
  summary?: string | null;
  content?: string | null;
  image: string;
  author?: string | null;
  views: number;
  status: 'published' | 'draft';
  created_at: string;
}

export interface Quote {
  id: number;
  fullname: string;
  phone: string;
  email?: string | null;
  service_type?: string | null;
  project_location?: string | null;
  message?: string | null;
  status: 'new' | 'processing' | 'completed' | 'canceled';
  created_at: string;
}

export interface Contact {
  id: number;
  name: string;
  email: string;
  phone?: string | null;
  subject?: string | null;
  message: string;
  status: 'unread' | 'read' | 'replied';
  created_at: string;
}

export interface SiteSettings {
  site_name?: string;
  company_short_name?: string;
  phone?: string;
  hotline?: string;
  email?: string;
  address?: string;
  factory_address?: string;
  working_hours?: string;
  hero_title?: string;
  hero_subtitle?: string;
  about_summary?: string;
  facebook_url?: string;
  youtube_url?: string;
  zalo_url?: string;
  [key: string]: string | undefined;
}

export interface DashboardStats {
  stats: {
    projects: {
      total: number;
      published: number;
      draft: number;
      co_khi: number;
      xay_dung: number;
    };
    services: {
      total: number;
      featured: number;
      co_khi: number;
      xay_dung: number;
    };
    news: {
      total: number;
    };
    quotes: {
      total: number;
      new: number;
      processing: number;
      completed: number;
    };
    contacts: {
      total: number;
      unread: number;
    };
    users: {
      total: number;
    };
  };
  recent_quotes: Quote[];
  recent_contacts: Contact[];
  recent_projects: Project[];
  monthly_trends: {
    month: string;
    quotes: number;
    contacts: number;
    projects: number;
  }[];
}

export interface ApiResponse<T = any> {
  success: boolean;
  message: string;
  data: T;
  timestamp?: string;
}
