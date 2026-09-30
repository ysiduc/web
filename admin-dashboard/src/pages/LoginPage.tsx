import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuthStore } from '../store/authStore';
import { HardHat, Lock, User, AlertCircle, ArrowRight, ShieldCheck } from 'lucide-react';

export const LoginPage: React.FC = () => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const { login, error, clearError } = useAuthStore();
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    clearError();
    if (!username.trim() || !password.trim()) return;

    setIsSubmitting(true);
    const success = await login(username.trim(), password.trim());
    setIsSubmitting(false);

    if (success) {
      navigate('/');
    }
  };

  const handleQuickFill = (u: string, p: string) => {
    setUsername(u);
    setPassword(p);
    clearError();
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-slate-100 dark:bg-navy-950 p-4 sm:p-6">
      <div className="w-full max-w-md">
        {/* Brand Logo & Header */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-gold-500 to-amber-400 text-navy-950 shadow-lg shadow-gold-500/25 mb-4">
            <HardHat className="w-9 h-9" />
          </div>
          <h1 className="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            PNMEC ADMIN PORTAL
          </h1>
          <p className="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Đăng nhập hệ thống quản lý Cơ Khí &amp; Xây Dựng
          </p>
        </div>

        {/* Login Card */}
        <div className="bg-white dark:bg-navy-900 rounded-3xl p-8 shadow-xl border border-slate-200 dark:border-navy-800">
          {error && (
            <div className="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 flex items-start gap-3">
              <AlertCircle className="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" />
              <div className="text-sm text-rose-700 dark:text-rose-300 font-medium">
                {error}
              </div>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-5">
            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                Tài khoản đăng nhập
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <User className="w-5 h-5" />
                </div>
                <input
                  type="text"
                  required
                  value={username}
                  onChange={(e) => setUsername(e.target.value)}
                  placeholder="admin hoặc staff"
                  className="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                Mật khẩu bảo mật
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <Lock className="w-5 h-5" />
                </div>
                <input
                  type="password"
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••••"
                  className="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 dark:border-navy-700 bg-white dark:bg-navy-950 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                />
              </div>
            </div>

            <button
              type="submit"
              disabled={isSubmitting}
              className="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-gold-500 to-amber-500 hover:from-gold-600 hover:to-amber-600 text-navy-950 font-bold text-sm shadow-lg shadow-gold-500/25 flex items-center justify-center gap-2 transition disabled:opacity-60 cursor-pointer"
            >
              {isSubmitting ? (
                <>
                  <svg className="animate-spin h-5 w-5 text-navy-950" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                  </svg>
                  <span>Đang đăng nhập...</span>
                </>
              ) : (
                <>
                  <span>Đăng Nhập Quản Trị</span>
                  <ArrowRight className="w-4 h-4" />
                </>
              )}
            </button>
          </form>

          {/* Quick login badges */}
          <div className="mt-8 pt-6 border-t border-slate-100 dark:border-navy-800">
            <div className="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1.5">
              <ShieldCheck className="w-4 h-4 text-emerald-500" />
              <span>Tài khoản kiểm thử hệ thống:</span>
            </div>
            <div className="grid grid-cols-2 gap-2">
              <button
                type="button"
                onClick={() => handleQuickFill('admin', 'admin123')}
                className="px-3 py-2 text-left rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-navy-800 dark:hover:bg-navy-700/80 border border-slate-200 dark:border-navy-700 transition group"
              >
                <div className="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-amber-600 dark:group-hover:text-amber-400">
                  Admin Toàn Quyền
                </div>
                <div className="text-[11px] text-slate-400">admin / admin123</div>
              </button>

              <button
                type="button"
                onClick={() => handleQuickFill('staff', 'staff123')}
                className="px-3 py-2 text-left rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-navy-800 dark:hover:bg-navy-700/80 border border-slate-200 dark:border-navy-700 transition group"
              >
                <div className="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-amber-600 dark:group-hover:text-amber-400">
                  Nhân Viên (Staff)
                </div>
                <div className="text-[11px] text-slate-400">staff / staff123</div>
              </button>
            </div>
          </div>
        </div>

        <div className="text-center mt-6 text-xs text-slate-500 dark:text-slate-400">
          © 2026 PNMEC - Công Ty CP Cơ Khí &amp; Xây Dựng. Bảo mật phiên làm việc.
        </div>
      </div>
    </div>
  );
};
