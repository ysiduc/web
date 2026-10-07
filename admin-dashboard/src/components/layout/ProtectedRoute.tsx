import React, { useEffect } from 'react';
import { Navigate } from 'react-router-dom';
import { useAuthStore } from '../../store/authStore';
import { ShieldAlert } from 'lucide-react';

interface ProtectedRouteProps {
  children: React.ReactNode;
  requireAdmin?: boolean;
}

export const ProtectedRoute: React.FC<ProtectedRouteProps> = ({
  children,
  requireAdmin = false,
}) => {
  const { user, isAuthenticated, isLoading, checkAuth } = useAuthStore();

  useEffect(() => {
    if (!isAuthenticated && !user) {
      void checkAuth();
    }
  }, [checkAuth, isAuthenticated, user]);

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-navy-950">
        <div className="flex flex-col items-center gap-3">
          <div className="w-10 h-10 border-4 border-amber-500 border-t-transparent rounded-full animate-spin" />
          <p className="text-sm font-medium text-slate-500 dark:text-slate-400">
            Đang xác thực phiên làm việc...
          </p>
        </div>
      </div>
    );
  }

  if (!isAuthenticated || !user) {
    return <Navigate to="/login" replace />;
  }

  if (requireAdmin && user.role !== 'admin') {
    return (
      <div className="flex flex-col items-center justify-center p-12 text-center my-12 bg-white dark:bg-navy-900 rounded-2xl border border-slate-200 dark:border-navy-800 shadow-sm max-w-lg mx-auto">
        <div className="w-14 h-14 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-4">
          <ShieldAlert className="w-8 h-8" />
        </div>
        <h2 className="text-xl font-bold text-slate-900 dark:text-white mb-2">
          Quyền truy cập bị từ chối
        </h2>
        <p className="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
          Khu vực này yêu cầu đặc quyền Quản trị viên (Admin). Tài khoản hiện tại của bạn là Kỹ sư / Nhân viên (Staff).
        </p>
        <a
          href="#/"
          className="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-navy-950 font-bold text-sm shadow-md shadow-amber-500/20 transition"
        >
          Quay lại Bảng điều khiển
        </a>
      </div>
    );
  }

  return <>{children}</>;
};
