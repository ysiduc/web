import React from 'react';

interface BadgeProps {
  status: string;
  className?: string;
}

export const Badge: React.FC<BadgeProps> = ({ status, className = '' }) => {
  const getBadgeStyle = (val: string) => {
    switch (val?.toLowerCase()) {
      case 'published':
        return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800';
      case 'draft':
        return 'bg-slate-100 text-slate-700 border-slate-300 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700';
      case 'new':
      case 'unread':
        return 'bg-amber-50 text-amber-700 border-amber-300 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800 animate-pulse';
      case 'processing':
        return 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-800';
      case 'completed':
      case 'replied':
      case 'active':
        return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800';
      case 'canceled':
      case 'inactive':
        return 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-800';
      case 'read':
        return 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700';
      case 'admin':
        return 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800 font-bold';
      case 'staff':
        return 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-950/40 dark:text-cyan-400 dark:border-cyan-800';
      default:
        return 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
    }
  };

  const getLabel = (val: string) => {
    switch (val?.toLowerCase()) {
      case 'published': return 'Đã công khai';
      case 'draft': return 'Bản nháp';
      case 'new': return 'Mới';
      case 'processing': return 'Đang xử lý';
      case 'completed': return 'Hoàn tất';
      case 'canceled': return 'Đã hủy';
      case 'unread': return 'Chưa đọc';
      case 'read': return 'Đã xem';
      case 'replied': return 'Đã phản hồi';
      case 'active': return 'Hoạt động';
      case 'inactive': return 'Khóa';
      case 'admin': return 'Quản trị viên (Admin)';
      case 'staff': return 'Kỹ sư / Nhân viên';
      default: return val;
    }
  };

  return (
    <span
      className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ${getBadgeStyle(
        status
      )} ${className}`}
    >
      {getLabel(status)}
    </span>
  );
};
