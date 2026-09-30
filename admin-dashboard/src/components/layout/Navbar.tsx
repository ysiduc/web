import React from 'react';
import {
  Sun,
  Moon,
  LogOut,
  ExternalLink,
  Menu,
  Shield,
  User as UserIcon,
} from 'lucide-react';
import { useAuthStore } from '../../store/authStore';

interface NavbarProps {
  onToggleSidebar: () => void;
  title?: string;
}

export const Navbar: React.FC<NavbarProps> = ({ onToggleSidebar, title }) => {
  const { user, logout, darkMode, toggleDarkMode } = useAuthStore();

  return (
    <header className="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200 dark:border-navy-800 bg-white/90 dark:bg-navy-900/90 backdrop-blur px-4 sm:px-6">
      <div className="flex items-center gap-4">
        <button
          onClick={onToggleSidebar}
          className="lg:hidden p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition"
          aria-label="Toggle menu"
        >
          <Menu className="w-5 h-5" />
        </button>

        <div>
          <h1 className="text-lg font-bold text-slate-900 dark:text-white">
            {title || 'Bảng Điều Khiển Quản Trị'}
          </h1>
          <p className="text-xs text-slate-500 dark:text-slate-400 hidden sm:block">
            PNMEC Cơ Khí & Xây Dựng • Hệ thống quản trị nội dung
          </p>
        </div>
      </div>

      <div className="flex items-center gap-2 sm:gap-4">
        {/* View Public Website */}
        <a
          href="/test/web_cty/index.php"
          target="_blank"
          rel="noopener noreferrer"
          className="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-navy-800 dark:hover:bg-navy-700 rounded-lg transition"
        >
          <ExternalLink className="w-3.5 h-3.5" />
          <span>Xem Website</span>
        </a>

        {/* Dark Mode Toggle */}
        <button
          onClick={toggleDarkMode}
          className="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition"
          title={darkMode ? 'Chuyển sang Giao diện Sáng' : 'Chuyển sang Giao diện Tối'}
        >
          {darkMode ? <Sun className="w-5 h-5 text-amber-400" /> : <Moon className="w-5 h-5" />}
        </button>

        {/* User Profile */}
        <div className="flex items-center gap-3 pl-2 sm:pl-4 border-l border-slate-200 dark:border-navy-800">
          <div className="flex items-center gap-2.5">
            <div className="w-9 h-9 rounded-xl bg-gradient-to-tr from-gold-500 to-amber-400 text-navy-950 font-bold flex items-center justify-center text-sm shadow-sm">
              {user?.fullname ? user.fullname.charAt(0).toUpperCase() : <UserIcon className="w-4 h-4" />}
            </div>
            <div className="hidden md:block text-left">
              <div className="text-sm font-semibold text-slate-900 dark:text-white leading-tight flex items-center gap-1.5">
                {user?.fullname || user?.username}
                {user?.role === 'admin' && (
                  <span className="inline-flex items-center gap-0.5 px-1.5 py-0.2 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 border border-amber-300 dark:border-amber-800">
                    <Shield className="w-2.5 h-2.5" />
                    Admin
                  </span>
                )}
              </div>
              <div className="text-xs text-slate-500 dark:text-slate-400">
                {user?.role === 'admin' ? 'Quản trị viên' : 'Kỹ sư / Nhân viên'}
              </div>
            </div>
          </div>

          {/* Logout Button */}
          <button
            onClick={() => logout()}
            className="p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition"
            title="Đăng xuất"
          >
            <LogOut className="w-5 h-5" />
          </button>
        </div>
      </div>
    </header>
  );
};
