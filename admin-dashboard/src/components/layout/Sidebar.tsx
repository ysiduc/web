import React from 'react';
import { NavLink } from 'react-router-dom';
import {
  LayoutDashboard,
  Building2,
  Wrench,
  Newspaper,
  Calculator,
  MessageSquare,
  Users,
  Settings,
  ChevronLeft,
  HardHat,
  X,
} from 'lucide-react';
import { useAuthStore } from '../../store/authStore';

interface SidebarProps {
  isOpen: boolean;
  onClose: () => void;
  isCollapsed: boolean;
  onToggleCollapse: () => void;
}

export const Sidebar: React.FC<SidebarProps> = ({
  isOpen,
  onClose,
  isCollapsed,
  onToggleCollapse,
}) => {
  const { user } = useAuthStore();
  const isAdmin = user?.role === 'admin';

  const navItems = [
    {
      to: '/',
      label: 'Tổng Quan',
      icon: LayoutDashboard,
      badge: null,
    },
    {
      to: '/projects',
      label: 'Công Trình & Dự Án',
      icon: Building2,
      badge: null,
    },
    {
      to: '/services',
      label: 'Dịch Vụ Thi Công',
      icon: Wrench,
      badge: null,
    },
    {
      to: '/news',
      label: 'Tin Tức & Kỹ Thuật',
      icon: Newspaper,
      badge: null,
    },
    {
      to: '/quotes',
      label: 'Yêu Cầu Báo Giá',
      icon: Calculator,
      badge: null,
    },
    {
      to: '/contacts',
      label: 'Khách Hàng Liên Hệ',
      icon: MessageSquare,
      badge: null,
    },
    // Admin only links
    ...(isAdmin
      ? [
          {
            to: '/users',
            label: 'Quản Lý Nhân Viên',
            icon: Users,
            badge: 'Admin',
          },
          {
            to: '/settings',
            label: 'Cài Đặt Website',
            icon: Settings,
            badge: 'Admin',
          },
        ]
      : []),
  ];

  return (
    <>
      {/* Mobile backdrop */}
      {isOpen && (
        <div
          className="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
          onClick={onClose}
        />
      )}

      {/* Sidebar container */}
      <aside
        className={`fixed top-0 bottom-0 left-0 z-40 flex flex-col bg-white dark:bg-navy-900 border-r border-slate-200 dark:border-navy-800 transition-all duration-300 ${
          isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        } ${isCollapsed ? 'lg:w-20' : 'lg:w-64'} w-[82vw] max-w-xs sm:w-64`}
      >
        {/* Brand Header */}
        <div className="flex h-16 items-center justify-between px-4 border-b border-slate-100 dark:border-navy-800">
          <div className="flex items-center gap-3 overflow-hidden">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-gold-500 to-amber-600 flex items-center justify-center text-navy-950 font-black shadow-md flex-shrink-0">
              <HardHat className="w-6 h-6" />
            </div>
            {!isCollapsed && (
              <div className="flex flex-col">
                <span className="font-extrabold text-base tracking-wider text-slate-900 dark:text-white uppercase font-heading">
                  PNMEC
                </span>
                <span className="text-[10px] uppercase font-bold text-gold-600 dark:text-gold-400 tracking-wider">
                  Cơ Khí &amp; Xây Dựng
                </span>
              </div>
            )}
          </div>

          <div className="flex items-center gap-1">
            {/* Collapse toggle (desktop) */}
            <button
              onClick={onToggleCollapse}
              className="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-navy-800 transition"
              title={isCollapsed ? 'Mở rộng sidebar' : 'Thu gọn sidebar'}
            >
              <ChevronLeft
                className={`w-5 h-5 transition-transform duration-200 ${
                  isCollapsed ? 'rotate-180' : ''
                }`}
              />
            </button>

            {/* Mobile close */}
            <button
              onClick={onClose}
              className="lg:hidden w-11 h-11 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-navy-800 transition"
              aria-label="Đóng menu sidebar"
            >
              <X className="w-5 h-5" />
            </button>
          </div>
        </div>


        {/* Navigation list */}
        <div className="flex-1 overflow-y-auto px-3 py-4 space-y-1">
          <div className="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
            {!isCollapsed && 'Menu Quản Trị'}
          </div>

          {navItems.map((item) => {
            const Icon = item.icon;
            return (
              <NavLink
                key={item.to}
                to={item.to}
                end={item.to === '/'}
                onClick={() => onClose()}
                className={({ isActive }) =>
                  `flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all ${
                    isActive
                      ? 'bg-amber-500 text-navy-950 font-bold shadow-sm shadow-amber-500/20'
                      : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-navy-800 hover:text-slate-900 dark:hover:text-white'
                  } ${isCollapsed ? 'justify-center px-0' : ''}`
                }
                title={isCollapsed ? item.label : undefined}
              >
                <Icon className="w-5 h-5 flex-shrink-0" />
                {!isCollapsed && (
                  <span className="flex-1 truncate">{item.label}</span>
                )}
                {!isCollapsed && item.badge && (
                  <span className="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                    {item.badge}
                  </span>
                )}
              </NavLink>
            );
          })}
        </div>

        {/* Sidebar Footer */}
        {!isCollapsed && (
          <div className="p-4 border-t border-slate-100 dark:border-navy-800 text-xs text-slate-400 dark:text-slate-500">
            <div className="font-semibold text-slate-700 dark:text-slate-300 truncate">
              Công ty CP PNMEC
            </div>
            <div className="mt-0.5 text-[11px]">Phiên bản Dashboard v2.0</div>
          </div>
        )}
      </aside>
    </>
  );
};
